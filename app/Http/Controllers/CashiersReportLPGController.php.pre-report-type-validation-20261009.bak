<?php
namespace App\Http\Controllers;
//use Request;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ClientModel;
use App\Models\CashiersReportModel;
use App\Models\CashiersReportModel_P1;
use App\Models\CashiersReportModel_P5;
use App\Models\BankModel;

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
											->whereNull('deleted_at') // ignore deleted rows
										)],
			'teves_branch'   		=> ['required',Rule::unique('teves_cashiers_report')->where( 
										fn ($query) =>$query
											->where('report_date', $request->report_date)
											->where('teves_branch', $request->teves_branch)
											->where('shift', $request->shift) 
											->whereNull('deleted_at') // ignore deleted rows
										)],
			'forecourt_attendant'   => 'required',
			'cashiers_name'   		=> 'required',
			'shift'   				=> ['required',Rule::unique('teves_cashiers_report')->where( 
										fn ($query) =>$query
											->where('report_date', $request->report_date)
											->where('teves_branch', $request->teves_branch)
											->where('shift', $request->shift)
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
											->whereNull('deleted_at') // ignore deleted rows											
										)],
			'teves_branch'   		=> ['required',Rule::unique('teves_cashiers_report')->where( 
										fn ($query) =>$query
											->where('report_date', $request->report_date)
											->where('teves_branch', $request->teves_branch)
											->where('shift', $request->shift)
											->where('cashiers_report_id', '<>',  $request->CashiersReportId )
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
			$bank_list = BankModel::all();
			
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
			
		return view("pages.cashiers_report_form_lpg.index", compact('data','title','CashiersReportData','product_data','CashiersReportId','teves_branch','client_data','bank_list'));	
		
		}
		
	}



}