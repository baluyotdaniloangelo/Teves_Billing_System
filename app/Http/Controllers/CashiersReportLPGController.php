<?php
namespace App\Http\Controllers;
//use Request;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ClientModel;
use App\Models\SalesAgentModel;
use App\Models\CashiersReportModel;
use App\Models\CashiersReportModel_P1;
use App\Models\CashiersReportModel_P5;
use App\Models\BankModel;
use App\Models\SupplierModel;

use App\Models\SOBillingTransactionModel;
use App\Models\BillingTransactionModel;

use App\Models\ProductModel;
use App\Models\TevesBranchModel;
use Session;
use Validator;
use DataTables;
use Illuminate\Support\Facades\DB;
/*PDF*/
use PDF;
use Illuminate\Validation\Rule;

use Carbon\Carbon;

class CashiersReportLPGController extends Controller
{
	
	/*Load client Interface*/
	public function cashierReportLPG(){
		
		if(Session::has('loginID')){
			
			$title = "Cashier's Report";
			$data = array();
		
			$data = User::where('user_id', '=', Session::get('loginID'))->first();
			
			if($data->user_branch_access_type=='ALL'){
				
				$teves_branch = TevesBranchModel::all();
				
			
			}else{
				
				$userID = Session::get('loginID');
				
				$teves_branch = TevesBranchModel::leftJoin('teves_user_branch_access', function($q) use ($userID)
				{
					$q->on('teves_branch_table.branch_id', '=', 'teves_user_branch_access.branch_idx');
				})
							
							->where('teves_user_branch_access.user_idx', '=', $userID)
							->get([
							'teves_branch_table.branch_id',
							'teves_user_branch_access.user_idx',
							'teves_user_branch_access.branch_idx',
							'teves_branch_table.branch_code',
							'teves_branch_table.branch_name'
							]);
				
			}	
			
			return view("pages.cashiers_report_lpg.index", compact('data','title','teves_branch'));
			
		}
		
	}   
	
	/*Fetch client List using Datatable*/
	public function getCashierReportLPG(Request $request)
	{
		
		if(Session::has('loginID')){
			
			$current_user = Session::get('loginID');
			
		if ($request->ajax()) {


			$filterBranch   = $request->filter_branch;
			$filterDateFrom = $request->filter_date_from;
			$filterDateTo   = $request->filter_date_to;
			$filterShift    = $request->filter_shift;

			$search = trim($request->cashier_report_search ?? '');


			$data = CashiersReportModel::query()

				/*==================================================
				USER BRANCH ACCESS
				==================================================*/

				->where(function ($q) use ($current_user) {

					if (Session::get('user_branch_access_type') == "BYBRANCH") {

						$q->whereRaw(
							"teves_cashiers_report.teves_branch IN
							(
								SELECT branch_idx
								FROM teves_user_branch_access
								WHERE user_idx = ?
							)",
							[$current_user]
						);

					}

				})


				/*==================================================
				ACTIVE RECORDS
				==================================================*/

				->whereNull(
					'teves_cashiers_report.deleted_at'
				)


				/*==================================================
				CASHIER REPORT TYPE
				==================================================*/

				->where(
					'teves_cashiers_report.cashier_report_type',
					'LPG'
				)
				

				/*==================================================
				USER JOIN
				==================================================*/

				->leftJoin('user_tb', function ($join) {

					$join->on(
						'user_tb.user_id',
						'=',
						'teves_cashiers_report.user_idx'
					)
					->whereNull('user_tb.deleted_at');

				})


				/*==================================================
				BRANCH JOIN
				==================================================*/

				->leftJoin('teves_branch_table', function ($join) {

					$join->on(
						'teves_branch_table.branch_id',
						'=',
						'teves_cashiers_report.teves_branch'
					)
					->whereNull('teves_branch_table.deleted_at');

				});


			/*======================================================
			BRANCH FILTER
			======================================================*/

			if (!empty($filterBranch)) {

				$data->where(
					'teves_cashiers_report.teves_branch',
					$filterBranch
				);

			}


			/*======================================================
			DATE FROM
			======================================================*/

			if (!empty($filterDateFrom)) {

				$data->whereDate(
					'teves_cashiers_report.report_date',
					'>=',
					$filterDateFrom
				);

			}


			/*======================================================
			DATE TO
			======================================================*/

			if (!empty($filterDateTo)) {

				$data->whereDate(
					'teves_cashiers_report.report_date',
					'<=',
					$filterDateTo
				);

			}


			/*======================================================
			SHIFT FILTER
			======================================================*/

			if (!empty($filterShift)) {

				$data->where(
					'teves_cashiers_report.shift',
					$filterShift
				);

			}


			/*======================================================
			CUSTOM SEARCH
			======================================================*/

			if ($search !== '') {

				$searchValue = '%' . $search . '%';

				$data->where(function ($q) use ($searchValue) {

					$q->where(
						'teves_cashiers_report.cashiers_name',
						'LIKE',
						$searchValue
					)

					->orWhere(
						'teves_cashiers_report.forecourt_attendant',
						'LIKE',
						$searchValue
					)

					->orWhere(
						'user_tb.user_real_name',
						'LIKE',
						$searchValue
					)

					->orWhere(
						'teves_branch_table.branch_code',
						'LIKE',
						$searchValue
					)

					->orWhere(
						'teves_cashiers_report.shift',
						'LIKE',
						$searchValue
					);

				});

			}


			/*======================================================
			SELECT
			======================================================*/

			$data->select([
				'teves_cashiers_report.cashiers_report_id',
				'teves_cashiers_report.user_idx',
				'user_tb.user_real_name',
				'teves_branch_table.branch_code',
				'teves_cashiers_report.cashiers_name',
				'teves_cashiers_report.forecourt_attendant',
				'teves_cashiers_report.report_date',
				'teves_cashiers_report.shift',
				'teves_cashiers_report.created_at',
				'teves_cashiers_report.updated_at'
			]);


			return DataTables::of($data)
					->addIndexColumn()
					->addColumn('action', function($row){
						
						$startTimeStamp = strtotime($row->created_at);
						$endTimeStamp = strtotime(date('y-m-d'));
						$timeDiff = abs($endTimeStamp - $startTimeStamp);
						$numberDays = $timeDiff/86400;  // 86400 seconds in one day (3600 for 1hr)
						// and you might want to convert to integer
						$numberDays = intval($numberDays);
						
						if(Session::get('UserType')=="SUAdmin" || Session::get('UserType')=="Admin"){
							
							$actionBtn = '
							<div align="center" class="action_table_menu_client">
							<a href="#" data-id="'.$row->cashiers_report_id.'" class="btn-warning btn-circle btn-sm bi bi-printer-fill btn_icon_table btn_icon_table_view" onclick="printCashierReportPDF('.$row->cashiers_report_id.')"></a>
							<a href="cashiers_report_form_lpg/'.$row->cashiers_report_id.'" class="btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit" id="editCashiersReport"></a>
							<a href="#" data-id="'.$row->cashiers_report_id.'" class="btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete" id="deleteCashiersReport"></a>
							</div>';
							
						}
						else if( Session::get('UserType')=="Supervisor"){
							
							if($numberDays>=1){
								$actionBtn = '
								<div align="center" class="action_table_menu_client">
								<a href="#" data-id="'.$row->cashiers_report_id.'" class="btn-warning btn-circle btn-sm bi bi-printer-fill btn_icon_table btn_icon_table_view" onclick="printCashierReportPDF('.$row->cashiers_report_id.')"></a>
								</div>';
							}
							else{
								
								/*Only the Encoder of the Report is allowed to Edit*/
								if(Session::get('loginID')==$row->user_idx){
								
									$actionBtn = '
									<div align="center" class="action_table_menu_client">
									<a href="#" data-id="'.$row->cashiers_report_id.'" class="btn-warning btn-circle btn-sm bi bi-printer-fill btn_icon_table btn_icon_table_view" onclick="printCashierReportPDF('.$row->cashiers_report_id.')"></a>
									<a href="cashiers_report_form_lpg/'.$row->cashiers_report_id.'" class="btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit" id="editCashiersReport"></a>
									<a href="#" data-id="'.$row->cashiers_report_id.'" class="btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete" id="deleteCashiersReport"></a>
									</div>';
								
								}else{
									
									$actionBtn = '
									<div align="center" class="action_table_menu_client">
									<a href="#" data-id="'.$row->cashiers_report_id.'" class="btn-warning btn-circle btn-sm bi bi-printer-fill btn_icon_table btn_icon_table_view" onclick="printCashierReportPDF('.$row->cashiers_report_id.')"></a>
									</div>';
								
								}
								
								
							}
							
						}
						elseif(Session::get('UserType')=="Accounting_Staff"){
							
							$actionBtn = '
							<div align="center" class="action_table_menu_client">
							<a href="#" data-id="'.$row->cashiers_report_id.'" class="btn-warning btn-circle btn-sm bi bi-printer-fill btn_icon_table btn_icon_table_view" onclick="printCashierReportPDF('.$row->cashiers_report_id.')"></a>
							</div>';
							
						}
						else{
							
							/*If Older that or equal to 1 Day the Available Menu is View Only*/
							/*This is Compared to the Date Created*/
							if($numberDays>=1){
								$actionBtn = '
								<div align="center" class="action_table_menu_client">
								<a href="#" data-id="'.$row->cashiers_report_id.'" class="btn-warning btn-circle btn-sm bi bi-printer-fill btn_icon_table btn_icon_table_view" onclick="printCashierReportPDF('.$row->cashiers_report_id.')"></a>
								</div>';
							}
							else{
								
								/*Only the Encoder of the Report is allowed to Edit*/
								if(Session::get('loginID')==$row->user_idx){
								
									$actionBtn = '
									<div align="center" class="action_table_menu_client">
									<a href="#" data-id="'.$row->cashiers_report_id.'" class="btn-warning btn-circle btn-sm bi bi-printer-fill btn_icon_table btn_icon_table_view" onclick="printCashierReportPDF('.$row->cashiers_report_id.')"></a>
									<a href="cashiers_report_form_lpg/'.$row->cashiers_report_id.'" class="btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit" id="editCashiersReport"></a>
									<a href="#" data-id="'.$row->cashiers_report_id.'" class="btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete" id="deleteCashiersReport"></a>
									</div>';
								
								}else{
									
									$actionBtn = '
									<div align="center" class="action_table_menu_client">
									<a href="#" data-id="'.$row->cashiers_report_id.'" class="btn-warning btn-circle btn-sm bi bi-printer-fill btn_icon_table btn_icon_table_view" onclick="printCashierReportPDF('.$row->cashiers_report_id.')"></a>
									</div>';
								
								}
								
							}
							
						}
						
						return $actionBtn;
						
					})
					->addColumn('created_at_dt_format', function($row){						
						return $row->created_at;
					})
					
					->addColumn('updated_at_dt_format', function($row){		
					
						if($row->updated_at=="0000-00-00 00:00:00"){
							return "$row->updated_at";
						}else{
							return "0000-00-00 00:00:00";
						}
					})
					->rawColumns(['action'])
					->make(true);
		}
		}
    }

	public function create_cashier_report_lpg_post(Request $request){

		$request->validate([
			'report_date'   		=> ['required',Rule::unique('teves_cashiers_report')->where( 
										fn ($query) =>$query
											->where('report_date', $request->report_date)
											->where('teves_branch', $request->teves_branch)
											->where('shift', $request->shift)
											->where('shift', $request->shift)
											->where('cashier_report_type', 'LPG')
										->whereNull('deleted_at') // ignore deleted rows
										)],
			'teves_branch'   		=> ['required',Rule::unique('teves_cashiers_report')->where( 
										fn ($query) =>$query
											->where('report_date', $request->report_date)
											->where('teves_branch', $request->teves_branch)
											->where('shift', $request->shift) 
											->where('cashier_report_type', 'LPG')
										->whereNull('deleted_at') // ignore deleted rows
										)],
			'forecourt_attendant'   => 'required',
			'cashiers_name'   		=> 'required',
			'shift'   				=> ['required',Rule::unique('teves_cashiers_report')->where( 
										fn ($query) =>$query
											->where('report_date', $request->report_date)
											->where('teves_branch', $request->teves_branch)
											->where('shift', $request->shift)
											->where('cashier_report_type', 'LPG')
										->whereNull('deleted_at') // ignore deleted rows
										)]
        ], 
        [
			'teves_branch.required' => 'Branch is required',
			'report_date.required' => 'Report Date is required',
			'forecourt_attendant.required' => "Empoyee's on Duty is required",
			'cashiers_name.required' => "Cashier's Name is required",
			'shift.required' => "Shift is required"
        ]
		);
		
			@$_cashiers_report_no = CashiersReportModel::latest()->first()->cashiers_report_id;
			$cashiers_report_no = $_cashiers_report_no + 100;
			
			$CashiersReportCreate = new CashiersReportModel();
			$CashiersReportCreate->user_idx 				= Session::get('loginID');
			$CashiersReportCreate->cashiers_report_no 		= $cashiers_report_no;
			$CashiersReportCreate->teves_branch 			= $request->teves_branch;
			$CashiersReportCreate->cashiers_name 			= $request->cashiers_name;
			$CashiersReportCreate->forecourt_attendant 		= $request->forecourt_attendant;
			$CashiersReportCreate->report_date 				= $request->report_date;
			$CashiersReportCreate->cashier_report_remarks 	= $request->cashier_report_remarks;
			
			$CashiersReportCreate->cashier_report_type 		= "LPG";
			
			$CashiersReportCreate->shift 					= $request->shift;
			$CashiersReportCreate->created_by_user_id 		= Session::get('loginID');
			$result = $CashiersReportCreate->save();
			
			/*Get Last ID*/
			$last_transaction_id = $CashiersReportCreate->cashiers_report_id;
			
			/*Initialized Cash On Hand*/
			$CashOnHand = new CashiersReportModel_P5();
			$CashOnHand->cashiers_report_id 	= $last_transaction_id;
			$CashOnHand->user_idx 				= Session::get('loginID');
			$CashOnHand->one_thousand_deno 		= 0;
			$CashOnHand->five_hundred_deno	 	= 0;
			$CashOnHand->two_hundred_deno 		= 0;
			$CashOnHand->one_hundred_deno 		= 0;
			$CashOnHand->fifty_deno 			= 0;
			$CashOnHand->twenty_deno 			= 0;
			$CashOnHand->ten_deno 				= 0;
			$CashOnHand->five_deno 				= 0;
			$CashOnHand->one_deno 				= 0;
			$CashOnHand->twenty_five_cent_deno 	= 0;
			$CashOnHand->cash_drop 				= 0;
			$result = $CashOnHand->save();
			
			if($result){
				return response()->json(array('success' => "Cashier's Report Information Successfully Created!", 'cashiers_report_id' => $last_transaction_id), 200);
			}
			else{
				return response()->json(['success'=>"Error on Insert Cashier's Report Information"]);
			}
			
	}

	public function update_cashier_report_lpg_post(Request $request){
		
		$request->validate([
			'report_date'   		=> ['required',Rule::unique('teves_cashiers_report')->where( 
										fn ($query) =>$query
											->where('report_date', $request->report_date)
											->where('teves_branch', $request->teves_branch)
											->where('shift', $request->shift)
											->where('cashiers_report_id', '<>',  $request->CashiersReportId )
											->where('cashier_report_type', 'LPG')
										->whereNull('deleted_at') // ignore deleted rows											
										)],
			'teves_branch'   		=> ['required',Rule::unique('teves_cashiers_report')->where( 
										fn ($query) =>$query
											->where('report_date', $request->report_date)
											->where('teves_branch', $request->teves_branch)
											->where('shift', $request->shift)
											->where('cashiers_report_id', '<>',  $request->CashiersReportId )
											->where('cashier_report_type', 'LPG')
										->whereNull('deleted_at') // ignore deleted rows			
										)],
			'forecourt_attendant'   => 'required',
			'cashiers_name'   		=> 'required',
			'shift'   				=> ['required',Rule::unique('teves_cashiers_report')->where( 
										fn ($query) =>$query
											->where('report_date', $request->report_date)
											->where('teves_branch', $request->teves_branch)
											->where('shift', $request->shift)
											->where('cashiers_report_id', '<>',  $request->CashiersReportId )
											->where('cashier_report_type', 'LPG')
										->whereNull('deleted_at') // ignore deleted rows			
										)]
        ], 
        [
			'teves_branch.required' => 'Branch is required',
			'report_date.required' => 'Report Date is required',
			'forecourt_attendant.required' => "Empoyee's on Duty is required",
			'cashiers_name.required' => "Cashier's Name is required",
			'shift.required' => "Shift is required"
        ]
		);

			$CashiersReportCreate = new CashiersReportModel();
			$CashiersReportCreate = CashiersReportModel::find($request->CashiersReportId);
			$CashiersReportCreate->teves_branch 			= $request->teves_branch;
			$CashiersReportCreate->cashiers_name 			= $request->cashiers_name;
			$CashiersReportCreate->forecourt_attendant 		= $request->forecourt_attendant;
			$CashiersReportCreate->report_date 				= $request->report_date;
			$CashiersReportCreate->shift 					= $request->shift;
			$CashiersReportCreate->cashier_report_remarks 	= $request->cashier_report_remarks;
			$CashiersReportCreate->updated_by_user_id 		= Session::get('loginID');
			$result = $CashiersReportCreate->update();
			
			if($result){
				return response()->json(array('success' => "Cashier's Report Information Successfully Updated!", 'cashiers_report_id' => $request->CashiersReportId), 200);
			}
			else{
				return response()->json(['success'=>"Error on Insert Cashier's Report Information"]);
			}
			
	}

	/*Fetch client Information*/
	public function cashiers_report_info(Request $request){
		
		$CashiersReportID = $request->CashiersReportID;
		
		$data = CashiersReportModel::where('cashiers_report_id', $request->CashiersReportID)
			->join('user_tb', 'user_tb.user_id', '=', 'teves_cashiers_report.user_idx')
            ->get([				
			'teves_cashiers_report.cashiers_report_id',
			'user_tb.user_real_name',
			'teves_cashiers_report.teves_branch',
			'teves_cashiers_report.forecourt_attendant',
			'teves_cashiers_report.report_date',
			'teves_cashiers_report.shift',
			'teves_cashiers_report.cashier_report_remarks',
			'teves_cashiers_report.created_at',
			'teves_cashiers_report.updated_at']);
		
		return response()->json($data);
					
	}

	public function delete_cashiers_report_info(Request $request){		
			
		$CashiersReportID = $request->CashiersReportID;
		CashiersReportModel::find($CashiersReportID)->delete();
		
		/*Delete Component*/
		/*P1*/
		CashiersReportModel_P1::where('cashiers_report_id', $CashiersReportID)->delete();
		
		/*Delete SO and Billing ITEM*/
		SOBillingTransactionModel::where('cashiers_report_idx', $CashiersReportID)->delete();
		BillingTransactionModel::where('cashiers_report_idx', $CashiersReportID)->delete();
		
		return 'Deleted';
		
	}
	
	public function cashiers_report_form_lpg($CashiersReportId){
		
		
		if(Session::has('loginID')){
			
			$data = array();	
			
			$data = User::where('user_id', '=', Session::get('loginID'))->first();
			
			$client_data = ClientModel::all();
			$sales_agent_data = SalesAgentModel::all();
			$bank_list = BankModel::all();
		$supplier_data = SupplierModel::whereNull('deleted_at')
			->orderBy('supplier_name')
			->get();
			
			if($data->user_branch_access_type=='ALL'){
				
				$teves_branch = TevesBranchModel::all();
			
			}else{
				
				$userID = Session::get('loginID');
				
				$teves_branch = TevesBranchModel::leftJoin('teves_user_branch_access', function($q) use ($userID)
				{
					$q->on('teves_branch_table.branch_id', '=', 'teves_user_branch_access.branch_idx');
				})
							
							->where('teves_user_branch_access.user_idx', '=', $userID)
							->get([
							'teves_branch_table.branch_id',
							'teves_user_branch_access.user_idx',
							'teves_user_branch_access.branch_idx',
							'teves_branch_table.branch_code',
							'teves_branch_table.branch_name'
							]);
				
			}	
			
		$title = "Cashier's Report";
		
		//$product_data = ProductModel::all();
		$userId = Session::get('loginID');
		$product_data = ProductModel::select('teves_product_table.*')
		->join('teves_user_product_category_access as upca', function ($join) use ($userId) {
			$join->on('teves_product_table.category_idx', '=', 'upca.category_idx')
				 ->where('upca.user_idx', $userId);
		})
		->whereNull('teves_product_table.deleted_at')
		->orderBy('product_name')
		->get();
		
		$CashiersReportData = CashiersReportModel::where('cashiers_report_id', $CashiersReportId)
			->join('user_tb', 'user_tb.user_id', '=', 'teves_cashiers_report.user_idx')
            ->get([				
			'teves_cashiers_report.cashiers_report_id',
			'teves_cashiers_report.cashiers_report_no',
			'user_tb.user_real_name',
			'teves_cashiers_report.teves_branch',
			'teves_cashiers_report.cashiers_name',
			'teves_cashiers_report.forecourt_attendant',
			'teves_cashiers_report.report_date',
			'teves_cashiers_report.shift',
			'teves_cashiers_report.cashier_report_remarks',
			'teves_cashiers_report.created_at',
			'teves_cashiers_report.updated_at']);
			
		return view("pages.cashiers_report_form_lpg.index", compact('data','title','CashiersReportData','product_data','CashiersReportId','teves_branch','client_data','bank_list','sales_agent_data','supplier_data'));
		
		}
		
	}

    public function generate_lpg_cashier_report_pdf(Request $request)
    {
        $request->validate(['CashiersReportId' => ['required', 'integer']]);
        $reportId = (int) $request->CashiersReportId;

        $report = DB::table('teves_cashiers_report as report')
            ->leftJoin('user_tb as encoder', 'encoder.user_id', '=', 'report.user_idx')
            ->leftJoin('teves_branch_table as branch', 'branch.branch_id', '=', 'report.teves_branch')
            ->where('report.cashiers_report_id', $reportId)
            ->where('report.cashier_report_type', 'LPG')
            ->whereNull('report.deleted_at')
            ->select([
                'report.cashiers_report_id', 'report.cashiers_report_no', 'report.report_date',
                'report.cashiers_name', 'report.forecourt_attendant', 'report.shift',
                'report.cashier_report_remarks', 'encoder.user_real_name as encoder_name',
                'report.teves_branch', 'branch.branch_code', 'branch.branch_name', 'branch.branch_address',
            ])
            ->first();

        abort_if(!$report, 404, 'LPG cashier report not found.');

        $sellOut = app(\App\Services\LpgSellOutSummary::class);
        $lpgCategoryIds = array_map('intval', config('lpg_inventory.empty_container_category_ids', [2]));
        $lpgRecords = $sellOut->records($reportId)->whereIn('category_idx', $lpgCategoryIds)->values();
        $sellOutRows = app(\App\Services\LpgActualInventorySummary::class)
            ->rows($reportId, $sellOut->rows($lpgRecords, $reportId));
        $openingBalances = $sellOut->openingBalances($reportId);

        // Include every active product in the LPG category, even with no report entries.
        $productCatalog = DB::table('teves_product_table as product')
            ->leftJoin('teves_product_main_table as main', function ($join) {
                $join->on('main.product_main_id', '=', 'product.product_main_idx')->whereNull('main.deleted_at');
            })
            ->whereIn('product.category_idx', $lpgCategoryIds)->whereNull('product.deleted_at')
            ->orderBy('main.product_name')->orderBy('product.product_name')
            ->get(['product.product_id', 'product.product_name', 'product.product_main_idx',
                'product.product_unit_measurement', 'product.product_capacity', 'product.product_capacity_unit',
                'main.product_name as group_name']);
        $rowsByProduct = $sellOutRows->keyBy('product_idx');
        foreach ($productCatalog as $product) {
            $row = $rowsByProduct->get((int) $product->product_id);
            if (!$row) {
                $row = [
                    'product_idx' => (int) $product->product_id, 'product_name' => $product->product_name,
                    'product_unit_measurement' => $product->product_unit_measurement,
                    'product_capacity' => $product->product_capacity, 'product_capacity_unit' => $product->product_capacity_unit,
                ];
                foreach (\App\Services\LpgSellOutSummary::MODES as $mode) {
                    $key = strtolower($mode);
                    $row[$key . '_quantity'] = 0;
                    $row[$key . '_count'] = 0;
                    if (in_array($mode, ['REFILL_SWAP', 'CYLINDER_WITH_CONTENT', 'UNCLASSIFIED'], true)) {
                        $row[$key . '_unit_price'] = null;
                        $row[$key . '_mixed_prices'] = false;
                        $row[$key . '_amount'] = 0;
                    }
                }
                $row['total_sales'] = 0;
                $row['net_sales'] = 0;
                $row['ending_quantity'] = null;
                $row['theoretical_ending_quantity'] = null;
                $row['actual_quantity'] = null;
                $row['variance_quantity'] = null;
                $opening = $openingBalances->get((int) $product->product_id);
                if ($opening) {
                    $row['beginning_count'] = 1;
                    $row['beginning_quantity'] = $row['beginning_filled_quantity'] = $opening['filled'];
                    $row['beginning_bo_returned_customer_quantity'] = $opening['bo_customer'];
                    $row['beginning_bo_during_delivery_quantity'] = $opening['bo_delivery'];
                    $row['audit_date'] = $opening['audit_date'];
                    $row['ending_quantity'] = $row['theoretical_ending_quantity'] = $opening['filled'];
                    $row['actual_quantity'] = $opening['filled'];
                    $row['theoretical_bo_returned_customer_quantity'] = $opening['bo_customer'];
                    $row['theoretical_bo_during_delivery_quantity'] = $opening['bo_delivery'];
                }
            }
            $row['print_group_key'] = $product->product_main_idx
                ? 'main:' . $product->product_main_idx : 'ungrouped';
            $row['print_group_name'] = $product->group_name ?? '';
            $rowsByProduct->put((int) $product->product_id, $row);
        }
        $sellOutRows = $rowsByProduct->sortBy(function ($row) {
            return ($row['print_group_name'] ?? '') . ' ' . $row['product_name'];
        }, SORT_NATURAL | SORT_FLAG_CASE)->values();

        // Use the same configured catalog, set components and totals as the Kitchen screen.
        $kitchenSummary = app(\App\Services\LpgKitchenSummary::class);
        $kitchenRows = $kitchenSummary->withCatalog($reportId, $kitchenSummary->rows($kitchenSummary->records($reportId)));
        $kitchenRows = app(\App\Services\LpgActualInventorySummary::class)->rows($reportId, $kitchenRows);
        $kitchenUnknownSetSales = app(\App\Services\LpgSetComponentSales::class)->forReport($reportId)['unknown_sales'];

        // Supporting sections from the cashier's report workbook.
        $arCollectionRows = DB::table('teves_cashiers_report_lpg_ar as ar')
            ->leftJoin('teves_client_table as client', 'client.client_id', '=', 'ar.client_idx')
            ->where('ar.cashiers_report_id', $reportId)->whereNull('ar.deleted_at')
            ->orderBy('ar.cashiers_report_lpg_ar_id')
            ->get(['client.client_name', 'client.client_account_number', 'ar.dr_number', 'ar.remarks', 'ar.amount_received']);

        $miscellaneousRows = DB::table('teves_cashiers_report_lpg_ar_so as sale')
            ->leftJoin('teves_client_table as client', 'client.client_id', '=', 'sale.client_idx')
            ->leftJoin('teves_product_table as product', 'product.product_id', '=', 'sale.product_idx')
            ->where('sale.cashiers_report_id', $reportId)->whereNull('sale.deleted_at')
            ->whereIn('sale.item_description', ['Sales Order', 'Return'])
            ->orderBy('sale.cashiers_report_lpg_ar_so_id')
            ->get(['client.client_name', 'client.client_account_number', 'sale.dr_number', 'sale.item_description',
                'product.product_name', 'product.product_unit_measurement', 'product.product_capacity', 'product.product_capacity_unit',
                'sale.order_quantity', 'sale.unit_price', 'sale.order_total_amount']);

        $expenseRows = DB::table('teves_cashiers_report_lpg_expenses as expense')
            ->leftJoin('teves_client_table as client', 'client.client_id', '=', 'expense.client_idx')
            ->leftJoin('teves_product_table as product', 'product.product_id', '=', 'expense.product_idx')
            ->where('expense.cashiers_report_id', $reportId)->whereNull('expense.deleted_at')
            ->orderBy('expense.cashiers_report_lpg_expenses_id')
            ->get(['client.client_name', 'expense.expense_type', 'expense.purpose',
                'product.product_name', 'product.product_unit_measurement', 'expense.item_description',
                'expense.order_quantity', 'expense.unit_price', 'expense.amount']);

        $discountRows = DB::table('teves_cashiers_report_lpg_ar_so as sale')
            ->leftJoin('teves_client_table as client', 'client.client_id', '=', 'sale.client_idx')
            ->leftJoin('teves_product_table as product', 'product.product_id', '=', 'sale.product_idx')
            ->where('sale.cashiers_report_id', $reportId)->whereNull('sale.deleted_at')
            ->where('sale.discount_per_unit', '>', 0)
            ->orderBy('sale.cashiers_report_lpg_ar_so_id')
            ->get(['client.client_name', 'client.client_account_number', 'sale.dr_number', 'sale.item_description',
                'product.product_name', 'product.product_unit_measurement', 'product.product_capacity', 'product.product_capacity_unit',
                'sale.order_quantity', 'sale.unit_price', 'sale.discount_per_unit',
                DB::raw('(sale.unit_price - sale.discount_per_unit) as discounted_unit_price'),
                DB::raw('(sale.order_quantity * sale.discount_per_unit) as total_discount')]);

        $cashDenominations = [
            ['label' => '1,000.00', 'value' => 1000, 'field' => 'one_thousand_deno'],
            ['label' => '500.00', 'value' => 500, 'field' => 'five_hundred_deno'],
            ['label' => '200.00', 'value' => 200, 'field' => 'two_hundred_deno'],
            ['label' => '100.00', 'value' => 100, 'field' => 'one_hundred_deno'],
            ['label' => '50.00', 'value' => 50, 'field' => 'fifty_deno'],
            ['label' => '20.00', 'value' => 20, 'field' => 'twenty_deno'],
            ['label' => '10.00', 'value' => 10, 'field' => 'ten_deno'],
            ['label' => '5.00', 'value' => 5, 'field' => 'five_deno'],
            ['label' => '1.00', 'value' => 1, 'field' => 'one_deno'],
            ['label' => '0.25', 'value' => 0.25, 'field' => 'twenty_five_cent_deno'],
        ];
        $cashRecord = DB::table('teves_cashiers_report_lpg_cash')
            ->where('cashiers_report_id', $reportId)->whereNull('deleted_at')->first();
        $cashOnHand = collect($cashDenominations)->sum(function ($denomination) use ($cashRecord) {
            return $denomination['value'] * (int) ($cashRecord->{$denomination['field']} ?? 0);
        });
        $cashDrop = round((float) ($cashRecord->cash_drop ?? 0), 2);
        $actualCash = round($cashOnHand + $cashDrop, 2);

        $nonCashRows = DB::table('teves_cashiers_report_lpg_non_cash_payments')
            ->where('cashiers_report_id', $reportId)->whereNull('deleted_at')
            ->orderBy('cashiers_report_lpg_non_cash_payment_id')->get();
        $nonCashTotals = [
            'gcash' => round((float) $nonCashRows->where('mode_of_payment', 'gcash')->sum('amount'), 2),
            'limitless' => round((float) $nonCashRows->where('mode_of_payment', 'limitless')->sum('amount'), 2),
            'bank_transfer' => round((float) $nonCashRows->where('mode_of_payment', 'credit_debit')->sum('amount'), 2),
            'check' => round((float) $nonCashRows->where('mode_of_payment', 'check')->sum('amount'), 2),
        ];
        $nonCashTotal = round((float) $nonCashRows->sum('amount'), 2);
        $arCollectionsTotal = round((float) $arCollectionRows->sum('amount_received'), 2);
        $lpgSalesTotal = round((float) $sellOutRows->sum('total_sales'), 2);
        $kitchenSalesTotal = round((float) $kitchenRows->sum('total_sales'), 2);
        $totalSales = round($lpgSalesTotal + $kitchenSalesTotal, 2);
        $miscellaneousTotal = round((float) $miscellaneousRows->sum('order_total_amount'), 2);
        $expensesTotal = round((float) $expenseRows->sum('amount'), 2);
        $discountsTotal = round((float) $discountRows->sum('total_discount'), 2);
        $theoreticalSales = round($totalSales - $miscellaneousTotal - $expensesTotal - $discountsTotal, 2);
        $totalCashAndNonCash = round($actualCash + $nonCashTotal, 2);
        $cashBeforeArCollections = round($totalCashAndNonCash - $arCollectionsTotal, 2);
        $salesVariance = round($cashBeforeArCollections - $theoreticalSales, 2);

        $emptyInventorySummary = app(\App\Services\LpgEmptyInventorySummary::class);
        $emptyInventoryRowsByProduct = $emptyInventorySummary->rows($reportId)->keyBy('product_idx');
        $emptyInventorySources = $emptyInventorySummary->sources($reportId);
        $emptyProductCatalog = DB::table('teves_product_table as product')
            ->whereIn('product.category_idx', \App\Services\LpgEmptyInventorySummary::categoryIds())
            ->whereNotIn('product.product_id', \App\Services\LpgRegulatorProducts::ids())
            ->whereNull('product.deleted_at')
            ->orderByRaw("CASE product.product_capacity_unit WHEN 'kg' THEN 0 WHEN 'g' THEN 1 ELSE 2 END")
            ->orderBy('product.product_capacity')->orderBy('product.product_name')
            ->get(['product.product_id', 'product.product_name', 'product.product_capacity', 'product.product_capacity_unit']);
        foreach ($emptyProductCatalog as $product) {
            $row = $emptyInventoryRowsByProduct->get((int) $product->product_id);
            if (!$row) {
                $row = new \App\Models\CashiersReportModel_LPG_EmptyInventory([
                    'cashiers_report_id' => $reportId, 'product_idx' => (int) $product->product_id,
                    'beginning_quantity' => 0, 'refill_sell_out_quantity' => 0, 'return_quantity' => 0,
                    'swap_in_quantity' => 0, 'from_other_branch_quantity' => 0, 'sold_quantity' => 0,
                    'swap_out_quantity' => 0, 'borrow_quantity' => 0, 'pull_out_branch_quantity' => 0,
                    'pull_out_truck_quantity' => 0, 'remarks' => null,
                ]);
                $row->cashiers_report_lpg_empty_inventory_id = 0;
            }
            $row->product_name = $product->product_name;
            $row->product_capacity = $product->product_capacity;
            $row->product_capacity_unit = $product->product_capacity_unit;
            $emptyInventoryRowsByProduct->put((int) $product->product_id, $emptyInventorySummary->decorate($row, $emptyInventorySources));
        }
        $emptyInventoryRows = $emptyInventoryRowsByProduct->values()->sortBy(function ($row) {
            $unit = $row->product_capacity_unit === 'kg' ? 0 : ($row->product_capacity_unit === 'g' ? 1 : 2);
            return sprintf('%d:%012.3f:%s', $unit, (float) $row->product_capacity, $row->product_name);
        }, SORT_NATURAL | SORT_FLAG_CASE)->values();
        $emptyInventoryGroups = $emptyInventoryRows->groupBy('capacity_group');

        $swappingRows = DB::table('teves_cashiers_report_lpg_swapping as swap')
            ->leftJoin('teves_client_table as client', 'client.client_id', '=', 'swap.client_idx')
            ->leftJoin('teves_product_table as received', 'received.product_id', '=', 'swap.received_product_idx')
            ->leftJoin('teves_product_table as replaced', 'replaced.product_id', '=', 'swap.replaced_product_idx')
            ->where('swap.cashiers_report_id', $reportId)->whereNull('swap.deleted_at')
            ->where(function ($query) { $query->where('swap.received_quantity', '>', 0)->orWhere('swap.replaced_quantity', '>', 0); })
            ->orderBy('swap.cashiers_report_lpg_swapping_id')
            ->get(['client.client_name', 'swap.received_quantity', 'swap.received_stock_state',
                'received.product_name as received_product_name', 'received.product_capacity as received_product_capacity',
                'received.product_capacity_unit as received_product_capacity_unit', 'received.product_unit_measurement as received_product_unit_measurement',
                'swap.replaced_quantity', 'swap.replaced_stock_state', 'replaced.product_name as replaced_product_name',
                'replaced.product_capacity as replaced_product_capacity', 'replaced.product_capacity_unit as replaced_product_capacity_unit',
                'replaced.product_unit_measurement as replaced_product_unit_measurement', 'swap.remarks']);

        $onLoanRows = DB::table('teves_cashiers_report_lpg_on_loan as loan')
            ->leftJoin('teves_client_table as client', 'client.client_id', '=', 'loan.client_idx')
            ->leftJoin('teves_product_table as borrowed', 'borrowed.product_id', '=', 'loan.borrow_product_idx')
            ->leftJoin('teves_product_table as returned', 'returned.product_id', '=', 'loan.return_product_idx')
            ->where('loan.cashiers_report_id', $reportId)->whereNull('loan.deleted_at')
            ->where(function ($query) {
                $query->where('loan.borrow_quantity', '>', 0)->orWhere('loan.borrow_alum_quantity', '>', 0)
                    ->orWhere('loan.return_quantity', '>', 0)->orWhere('loan.return_alum_quantity', '>', 0);
            })
            ->orderBy('loan.cashiers_report_lpg_on_loan_id')
            ->get(['client.client_name', 'loan.dr_number', 'loan.borrow_quantity', 'loan.borrow_stock_state',
                'borrowed.product_name as borrow_product_name', 'borrowed.product_capacity as borrow_product_capacity',
                'borrowed.product_capacity_unit as borrow_product_capacity_unit', 'borrowed.product_unit_measurement as borrow_product_unit_measurement',
                'loan.borrow_alum_quantity', 'loan.return_quantity', 'loan.return_stock_state',
                'returned.product_name as return_product_name', 'returned.product_capacity as return_product_capacity',
                'returned.product_capacity_unit as return_product_capacity_unit', 'returned.product_unit_measurement as return_product_unit_measurement',
                'loan.return_alum_quantity', 'loan.remarks']);

        return PDF::loadView('printables.cashier_report_lpg_pdf', compact(
            'report', 'sellOutRows', 'kitchenRows', 'kitchenUnknownSetSales', 'arCollectionRows', 'miscellaneousRows', 'expenseRows', 'discountRows',
            'cashDenominations', 'cashRecord', 'cashOnHand', 'cashDrop', 'actualCash', 'nonCashRows', 'nonCashTotals',
            'nonCashTotal', 'arCollectionsTotal', 'lpgSalesTotal', 'kitchenSalesTotal', 'totalSales',
            'miscellaneousTotal', 'expensesTotal', 'discountsTotal', 'theoreticalSales', 'totalCashAndNonCash',
            'cashBeforeArCollections', 'salesVariance', 'emptyInventoryRows', 'emptyInventoryGroups', 'swappingRows', 'onLoanRows'
        ))
            ->setPaper([0, 0, 612, 936], 'portrait')->stream('lpg-cashier-report-' . $reportId . '.pdf');
    }



}
