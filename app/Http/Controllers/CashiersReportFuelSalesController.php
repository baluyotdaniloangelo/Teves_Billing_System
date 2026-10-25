<?php
namespace App\Http\Controllers;
//use Request;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ClientModel;
use App\Models\CashiersReportModel;
use App\Models\CashiersReportModel_P1;

use App\Models\SOBillingTransactionModel;
use App\Models\BillingTransactionModel;

use App\Models\ProductModel;
use App\Models\ProductPricePerBranchModel;
use App\Models\ProductPricePerBranchHistoryModel;
use App\Models\TevesBranchModel;
use Session;
use Validator;

use DataTables;
use Illuminate\Support\Facades\DB;

use Illuminate\Validation\Rule;
use Carbon\Carbon;

class CashiersReportFuelSalesController extends Controller
{
	/**
	 * Ensure each configured fuel pump has a Fuel Sales row for this report.
	 * This is safe to call on every report load: rows are keyed by report and pump.
	 */
	public function initializeFuelSalesRows(Request $request)
	{
		$request->validate([
			'CashiersReportId' => 'required|integer|exists:teves_cashiers_report,cashiers_report_id',
		]);

		$userId = Session::get('loginID');
		$report = CashiersReportModel::findOrFail($request->CashiersReportId);
		$user = User::where('user_id', $userId)->first();

		if (!$userId || !$user) {
			return response()->json(['message' => 'Unauthenticated.'], 401);
		}

		if ($user->user_branch_access_type !== 'ALL') {
			$hasBranchAccess = DB::table('teves_user_branch_access')
				->where('user_idx', $userId)
				->where('branch_idx', $report->teves_branch)
				->exists();

			if (!$hasBranchAccess) {
				return response()->json(['message' => 'You do not have access to this branch report.'], 403);
			}
		}

		$created = 0;
		$skipped = [];
		$alreadyInitialized = false;

		DB::transaction(function () use ($report, $userId, &$created, &$skipped, &$alreadyInitialized) {
			// Serialize initialization for this report so parallel page requests do not duplicate rows.
			CashiersReportModel::where('cashiers_report_id', $report->cashiers_report_id)
				->lockForUpdate()
				->first();

			// Seed only an empty report. This prevents deleted rows from being recreated
			// on refresh and leaves all manually entered rows untouched.
			if (CashiersReportModel_P1::where('cashiers_report_id', $report->cashiers_report_id)->exists()) {
				$alreadyInitialized = true;
				return;
			}

			$pumps = DB::table('teves_product_pump_table as pump')
				->join('teves_product_table as product', 'product.product_id', '=', 'pump.product_idx')
				->join('teves_product_category as category', 'category.category_id', '=', 'product.category_idx')
				->join('teves_user_product_category_access as access', function ($join) use ($userId) {
					$join->on('access.category_idx', '=', 'product.category_idx')
						->where('access.user_idx', '=', $userId);
				})
				->where('pump.branch_idx', $report->teves_branch)
				->whereNull('pump.deleted_at')
				->whereNull('product.deleted_at')
				->whereNull('category.deleted_at')
				->whereRaw('LOWER(TRIM(category.category_name)) LIKE ?', ['%fuel%'])
				->orderBy('pump.pump_id')
				->get([
					'pump.pump_id', 'pump.product_idx', 'pump.initial_reading',
					'product.product_price', 'product.product_name', 'product.product_code',
				]);

			$previousRows = DB::table('teves_cashiers_report_p1 as fuel_sales')
				->join('teves_cashiers_report as previous_report', 'previous_report.cashiers_report_id', '=', 'fuel_sales.cashiers_report_id')
				->where('previous_report.teves_branch', $report->teves_branch)
				->whereNull('previous_report.deleted_at')
				->where(function ($query) use ($report) {
					$query->where('previous_report.report_date', '<', $report->report_date)
						->orWhere(function ($sameDate) use ($report) {
							$sameDate->where('previous_report.report_date', $report->report_date)
								->where('previous_report.cashiers_report_id', '<', $report->cashiers_report_id);
						});
				})
				->orderByDesc('previous_report.report_date')
				->orderByDesc('previous_report.cashiers_report_id')
				->get([
					'fuel_sales.pump_idx', 'fuel_sales.tank_idx',
					'fuel_sales.closing_reading', 'fuel_sales.product_price',
				]);

			$lastByPump = [];
			foreach ($previousRows as $previousRow) {
				if ($previousRow->pump_idx && !array_key_exists($previousRow->pump_idx, $lastByPump)) {
					$lastByPump[$previousRow->pump_idx] = $previousRow;
				}
			}

			foreach ($pumps as $pump) {
				$alreadyExists = CashiersReportModel_P1::where('cashiers_report_id', $report->cashiers_report_id)
					->where('pump_idx', $pump->pump_id)
					->exists();

				if ($alreadyExists) {
					continue;
				}

				$tanks = DB::table('teves_product_tank_table')
					->where('product_idx', $pump->product_idx)
					->where('branch_idx', $report->teves_branch)
					->orderBy('tank_id')
					->get(['tank_id', 'tank_name']);

				$previous = $lastByPump[$pump->pump_id] ?? null;
				$selectedTankId = $previous && $tanks->contains('tank_id', $previous->tank_idx)
					? $previous->tank_idx
					: ($tanks->count() === 1 ? $tanks->first()->tank_id : null);

				// There is no pump-to-tank mapping in the existing schema. Do not guess
				// when multiple tanks serve this product; report it for manual resolution.
				if (!$selectedTankId) {
					$skipped[] = [
						'product' => $pump->product_name,
						'pump_id' => $pump->pump_id,
					'reason' => $tanks->isEmpty() ? 'No tank is assigned.' : 'Multiple tanks are assigned and no previous tank mapping exists.',
					];
					continue;
				}

				$branchPrice = DB::table('teves_product_branch_price_table')
					->where('product_idx', $pump->product_idx)
					->where('branch_idx', $report->teves_branch)
					->value('branch_price');
				$price = $branchPrice !== null
					? (float) $branchPrice
					: (float) $pump->product_price;

				$sale = new CashiersReportModel_P1();
				$sale->user_idx = $userId;
				$sale->cashiers_report_id = $report->cashiers_report_id;
				$sale->product_idx = $pump->product_idx;
				$sale->tank_idx = $selectedTankId;
				$sale->pump_idx = $pump->pump_id;
				$sale->beginning_reading = 0;
				$sale->closing_reading = 0;
				$sale->calibration = 0;
				$sale->order_quantity = 0;
				$sale->product_price = $price;
				$sale->order_total_amount = 0;
				$sale->save();
				$created++;
			}
		});

		return response()->json([
			'success' => true,
			'created' => $created,
			'already_initialized' => $alreadyInitialized,
			'skipped' => $skipped,
		]);
	}

	public function get_fuel_sales_products(Request $request)
	{
		$userId = Session::get('loginID');
		$branchId = $request->input('branch_idx');

		if (!$userId) {
			return response()->json(['message' => 'Unauthenticated.'], 401);
		}

		$products = ProductModel::query()
			->leftJoin('teves_product_branch_price_table as product_branch_price', function ($join) use ($branchId) {
				$join->on('product_branch_price.product_idx', '=', 'teves_product_table.product_id')
					->where('product_branch_price.branch_idx', $branchId);
			})
			->join('teves_product_category as product_category', 'product_category.category_id', '=', 'teves_product_table.category_idx')
			->join('teves_user_product_category_access as user_category_access', 'user_category_access.category_idx', '=', 'teves_product_table.category_idx')
			->where('user_category_access.user_idx', $userId)
			->whereRaw('LOWER(TRIM(product_category.category_name)) LIKE ?', ['%fuel%'])
			->whereNull('product_category.deleted_at')
			->distinct()
			->orderBy('teves_product_table.product_name')
			->get([
				'teves_product_table.product_id',
				'teves_product_table.product_name',
				'teves_product_table.product_code',
				DB::raw('COALESCE(product_branch_price.branch_price, teves_product_table.product_price) as product_price')
			]);

		return response()->json($products);
	}
	

	public function cashiers_report_fuel_sales_info(Request $request){

		$CashiersReportId = $request->CashiersReportId;
		$product_id = $request->product_id;
		
		if($CashiersReportId!=0){
			
			$data =  CashiersReportModel_P1::where('cashiers_report_id', $CashiersReportId)
				->where('product_idx', $product_id)
				->skip(0)
				->take(1)
					->get([
						'teves_cashiers_report_p1.product_price'
						]);		
						
			return response()->json($data);
			
		}else{
		
			$CHPH1_ID = $request->CHPH1_ID;
			
			$data =  CashiersReportModel_P1::where('cashiers_report_p1_id', $CHPH1_ID)
				->join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_cashiers_report_p1.product_idx')
				->leftjoin('teves_product_tank_table', 'teves_product_tank_table.tank_id', '=', 'teves_cashiers_report_p1.tank_idx')
				->leftjoin('teves_product_pump_table', 'teves_product_pump_table.pump_id', '=', 'teves_cashiers_report_p1.pump_idx')
					->get([
						'teves_product_table.product_name',
						'teves_product_table.product_id',
						'teves_product_table.product_code',
						DB::raw('IFNULL(teves_product_tank_table.tank_name, "Please Select a Tank") as tank_name'),
						DB::raw('IFNULL(teves_product_pump_table.pump_name, "Please Select a Pump") as pump_name'),
						'teves_cashiers_report_p1.cashiers_report_p1_id',
						'teves_cashiers_report_p1.beginning_reading',
						'teves_cashiers_report_p1.closing_reading',
						'teves_cashiers_report_p1.calibration',
						'teves_cashiers_report_p1.order_quantity',
						'teves_cashiers_report_p1.product_price',
						'teves_cashiers_report_p1.order_total_amount'
						]);			
						
			return response()->json($data);
			
		}
	}
	
	public function save_cashiers_report_fuel_sales(Request $request){	

		$CHPH1_ID 				= $request->fuel_sales_id;
		$updateBranchPrice = $request->boolean('update_branch_price');
		
    $request->validate([
        'product_idx' => 'required',
        'tank_idx' => 'required',
        'pump_idx' => 'required',

        /*
        |--------------------------------------------------------------------------
        | BEGINNING READING
        |--------------------------------------------------------------------------
        | Must be unique based on:
        | Cashier's Report + Product + Tank + Pump + Beginning Reading
        |--------------------------------------------------------------------------
        */
        'beginning_reading' => [
            'required',

            Rule::unique('teves_cashiers_report_p1')
                ->where(function ($query) use ($request, $CHPH1_ID) {

                    $query
                        ->where(
                            'cashiers_report_id',
                            $request->CashiersReportId
                        )
                        ->where(
                            'product_idx',
                            $request->product_idx
                        )
                        ->where(
                            'tank_idx',
                            $request->tank_idx
                        )
                        ->where(
                            'pump_idx',
                            $request->pump_idx
                        )
                        ->where(
                            'beginning_reading',
                            $request->beginning_reading
                        );

                    /*
                    | Exclude current record when editing
                    */
                    if ($CHPH1_ID) {
                        $query->where(
                            'cashiers_report_p1_id',
                            '<>',
                            $CHPH1_ID
                        );
                    }

                    return $query;
                }),
        ],


        /*
        |--------------------------------------------------------------------------
        | CLOSING READING
        |--------------------------------------------------------------------------
        | Must be unique based on:
        | Cashier's Report + Product + Tank + Pump + Closing Reading
        |--------------------------------------------------------------------------
        */
				'closing_reading' => [
					'required',

					Rule::unique('teves_cashiers_report_p1')
						->where(function ($query) use ($request, $CHPH1_ID) {

							$query
								->where(
									'cashiers_report_id',
									$request->CashiersReportId
								)
								->where(
									'product_idx',
									$request->product_idx
								)
								->where(
									'tank_idx',
									$request->tank_idx
								)
								->where(
									'pump_idx',
									$request->pump_idx
								)
								->where(
									'closing_reading',
									$request->closing_reading
								);

							/*
							| Exclude current record when editing
							*/
							if ($CHPH1_ID) {
								$query->where(
									'cashiers_report_p1_id',
									'<>',
									$CHPH1_ID
								);
							}

							return $query;
						}),
				],

			], [

				'product_idx.required' =>
					'Product is Required',

				'tank_idx.required' =>
					'Tank is Required',

				'pump_idx.required' =>
					'Pump is Required',

				'beginning_reading.required' =>
					'Beginning Reading is Required',

				'closing_reading.required' =>
					'Closing Reading is Required',

			]);

		if ($updateBranchPrice) {
			if (!in_array(Session::get('UserType'), ['Admin', 'SUAdmin'], true)) {
				return response()->json(['message' => 'Only administrators can update branch fuel prices.'], 403);
			}

			$request->validate([
				'branch_idx' => 'required|integer|exists:teves_branch_table,branch_id',
				'product_manual_price' => 'required|numeric|min:0',
			]);
		}
			
			/*Get Last ID*/
			$CashiersReportId = $request->CashiersReportId;
			
			$product_idx				= $request->product_idx;
			$tank_idx					= $request->tank_idx;
			$pump_idx					= $request->pump_idx;
			$beginning_reading 			= $request->beginning_reading;
			$closing_reading 			= $request->closing_reading;
			$calibration 				= $request->calibration;
			$product_manual_price 		= $request->product_manual_price;

					/*Check if Price is From Manual Price*/
					if($product_manual_price!=0){
						
						$product_price = $request->product_manual_price;
						
					}else{
	
						/*Product Details*/
						$raw_query_product = "SELECT a.product_id, ifnull(b.branch_price,a.product_price) AS product_price FROM teves_product_table AS a
						LEFT JOIN teves_product_branch_price_table b ON b.product_idx = a.product_id LEFT JOIN teves_branch_table c ON c.branch_id = b.branch_idx
						WHERE b.branch_idx = ? and b.product_idx = ?";			
						$product_info = DB::select("$raw_query_product", [$request->branch_idx,$request->product_idx]);		
						
						$product_price = $product_info[0]->product_price;

					}
						
								// Meter readings are entered to 2 decimal places. Normalize floating-point
								// residue so equal sales and calibration values persist as exactly zero.
								$order_quantity = round((float) $closing_reading - (float) $beginning_reading - (float) $calibration, 2);
								if (abs($order_quantity) < 0.005) {
									$order_quantity = 0.0;
								}
								$peso_sales = round($order_quantity * (float) $product_price, 2);
								if (abs($peso_sales) < 0.005) {
									$peso_sales = 0.0;
								}
								
								if($CHPH1_ID=='' || $CHPH1_ID ==0){	
								
									$CashiersReportModel_P1 = new CashiersReportModel_P1();
									
									$CashiersReportModel_P1->user_idx 					= Session::get('loginID');
									$CashiersReportModel_P1->cashiers_report_id 		= $CashiersReportId;
									$CashiersReportModel_P1->product_idx 				= $product_idx;
									$CashiersReportModel_P1->tank_idx 					= $tank_idx;
									$CashiersReportModel_P1->pump_idx 					= $pump_idx;
									$CashiersReportModel_P1->beginning_reading 			= $beginning_reading;
									$CashiersReportModel_P1->closing_reading 			= $closing_reading;
									$CashiersReportModel_P1->calibration 				= $calibration+0;
									$CashiersReportModel_P1->order_quantity 			= $order_quantity;
									$CashiersReportModel_P1->product_price 				= $product_price;
									$CashiersReportModel_P1->order_total_amount 		= $peso_sales;
									
									$result = DB::transaction(function () use ($CashiersReportModel_P1, $request, $product_idx, $product_price, $updateBranchPrice) {
										$saved = $CashiersReportModel_P1->save();
										if ($saved && $updateBranchPrice) {
											$this->updateFuelProductBranchPrice($product_idx, $request->branch_idx, $product_price);
										}
										return $saved;
									});
									
									if($result){
										return response()->json(['success'=>'Product Successfully Created!']);
									}
									else{
										return response()->json(['success'=>'Error on Product Information']);
									}
									
								}else{
																	
									$CashiersReportModel_P1 = new CashiersReportModel_P1();
									$CashiersReportModel_P1 = CashiersReportModel_P1::find($CHPH1_ID);
									
									//$CashiersReportModel_P1->cashiers_report_id 		= $CashiersReportId;
									$CashiersReportModel_P1->product_idx 				= $product_idx;
									$CashiersReportModel_P1->tank_idx 					= $tank_idx;
									$CashiersReportModel_P1->pump_idx 					= $pump_idx;
									$CashiersReportModel_P1->beginning_reading 			= $beginning_reading;
									$CashiersReportModel_P1->closing_reading 			= $closing_reading;
									$CashiersReportModel_P1->calibration 				= $calibration+0;
									$CashiersReportModel_P1->order_quantity 			= $order_quantity;
									$CashiersReportModel_P1->product_price 				= $product_price;
									$CashiersReportModel_P1->order_total_amount 		= $peso_sales;
									
									$result = DB::transaction(function () use ($CashiersReportModel_P1, $request, $product_idx, $product_price, $updateBranchPrice) {
										$updated = $CashiersReportModel_P1->update();
										if ($updated && $updateBranchPrice) {
											$this->updateFuelProductBranchPrice($product_idx, $request->branch_idx, $product_price);
										}
										return $updated;
									});
									
									if($result){
										return response()->json(['success'=>'Product Successfully Updated!']);
									}
									else{
										return response()->json(['success'=>'Error on Product Information']);
									}
									
								}
								
	}	

	private function updateFuelProductBranchPrice($productId, $branchId, $sellingPrice)
	{
		$sellingPrice = round((float) $sellingPrice, 2);
		$branchPrice = ProductPricePerBranchModel::where('product_idx', $productId)
			->where('branch_idx', $branchId)
			->first();
		$isNewPrice = !$branchPrice;

		if ($isNewPrice) {
			$branchPrice = new ProductPricePerBranchModel();
			$branchPrice->product_idx = $productId;
			$branchPrice->branch_idx = $branchId;
			$branchPrice->buying_price = 0;
			$branchPrice->profit_margin_type = 'Peso';
			$branchPrice->profit_margin = $sellingPrice;
			$branchPrice->profit_margin_in_peso = $sellingPrice;
			$branchPrice->created_by_user_idx = Session::get('loginID');
		} else {
			$buyingPrice = (float) ($branchPrice->buying_price ?? 0);
			$marginInPeso = round($sellingPrice - $buyingPrice, 2);
			$marginType = $branchPrice->profit_margin_type === 'Percentage' && $buyingPrice > 0
				? 'Percentage'
				: 'Peso';

			$branchPrice->buying_price = $buyingPrice;
			$branchPrice->profit_margin_type = $marginType;
			$branchPrice->profit_margin = $marginType === 'Percentage'
				? round(($marginInPeso / $buyingPrice) * 100, 2)
				: $marginInPeso;
			$branchPrice->profit_margin_in_peso = $marginInPeso;
		}

		$branchPrice->branch_price = $sellingPrice;
		$branchPrice->modified_by_user_idx = Session::get('loginID');
		$branchPrice->save();

		$history = new ProductPricePerBranchHistoryModel();
		$history->branch_price_idx = $branchPrice->branch_price_id;
		$history->product_idx = $productId;
		$history->branch_idx = $branchId;
		$history->date_of_changes = Carbon::now()->toDateString();
		$history->time_of_changes = Carbon::now()->format('H:i:s');
		$history->branch_price = $sellingPrice;
		$history->buying_price = $branchPrice->buying_price;
		$history->profit_margin_type = $branchPrice->profit_margin_type;
		$history->profit_margin = $branchPrice->profit_margin;
		$history->profit_margin_in_peso = $branchPrice->profit_margin_in_peso;
		$history->created_by_user_idx = Session::get('loginID');
		$history->save();
	}
	
	public function get_cashiers_report_fuel_sales(Request $request){		
	
		if ($request->ajax()) {

    	$data =  CashiersReportModel_P1::where('cashiers_report_id', $request->CashiersReportId)
				->join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_cashiers_report_p1.product_idx')
				->leftjoin('teves_product_tank_table', 'teves_product_tank_table.tank_id', '=', 'teves_cashiers_report_p1.tank_idx')
				->leftjoin('teves_product_pump_table', 'teves_product_pump_table.pump_id', '=', 'teves_cashiers_report_p1.pump_idx')
				->orderBy('cashiers_report_p1_id', 'asc')
              	->get([
					'teves_product_table.product_id as product_idx',
					'teves_product_table.product_name',
					'teves_product_table.product_code',
					DB::raw('IFNULL(teves_product_tank_table.tank_name, "No Tank selected") as tank_name'),
					DB::raw('IFNULL(teves_product_pump_table.pump_name, "No Pump Selected") as pump_name'),
					'teves_cashiers_report_p1.product_price',
					'teves_cashiers_report_p1.cashiers_report_p1_id',
					'teves_cashiers_report_p1.cashiers_report_id',
					'teves_cashiers_report_p1.beginning_reading',
					'teves_cashiers_report_p1.closing_reading',
					'teves_cashiers_report_p1.calibration',
					'teves_cashiers_report_p1.order_quantity',
					'teves_cashiers_report_p1.order_total_amount',
					'teves_cashiers_report_p1.created_at',
					'teves_cashiers_report_p1.updated_at'
					]);
		
		return DataTables::of($data)
				->addIndexColumn()
                ->addColumn('action', function($row){
					$actionBtn = '<div align="center" class="action_table_menu_Product">
					<a href="#" data-id="'.$row->cashiers_report_p1_id.'" class="btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit" id="EditFuelSales" title="Edit Fuel Sales"></a>
					<a href="#" data-id="'.$row->cashiers_report_p1_id.'" class="btn-warning btn-circle btn-sm bi bi-trash3-fill btn_icon_table btn_icon_table_delete" id="DeleteFuelSales" title="Delete Fuel Sales"></a>
					</div>';
                    return $actionBtn;
                })
				
				->rawColumns(['action'])
                ->make(true);
		}	
		
	}
	
	public function delete_cashiers_report_fuel_sales(Request $request){		
			
		$CHPH1_ID = $request->CHPH1_ID;
		CashiersReportModel_P1::find($CHPH1_ID)->delete();
		return 'Deleted';
		
	}

}
