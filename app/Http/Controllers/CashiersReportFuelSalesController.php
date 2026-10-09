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
use App\Models\TevesBranchModel;
use Session;
use Validator;

use DataTables;
use Illuminate\Support\Facades\DB;

use Illuminate\Validation\Rule;
use Carbon\Carbon;

class CashiersReportFuelSalesController extends Controller
{
	public function get_fuel_sales_products()
	{
		$userId = Session::get('loginID');

		if (!$userId) {
			return response()->json(['message' => 'Unauthenticated.'], 401);
		}

		$products = ProductModel::query()
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
				'teves_product_table.product_price'
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
									
									$result = $CashiersReportModel_P1->save();
									
									if($result){
										return response()->json(['success'=>'Product Successfully Created!']);
									}
									else{
										return response()->json(['success'=>'Error on Product Information']);
									}
									
								}else{
																	
									$CashiersReportModel_P1 = new CashiersReportModel_P1();
									$CashiersReportModel_P1 = CashiersReportModel_P1::find($CHPH1_ID);
									
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
									
									$result = $CashiersReportModel_P1->update();
									
									if($result){
										return response()->json(['success'=>'Product Successfully Updated!']);
									}
									else{
										return response()->json(['success'=>'Error on Product Information']);
									}
									
								}
								
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
