<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CashiersReportModel;
use App\Models\CashiersReportModel_P8;
use App\Models\ProductModel;
use App\Models\TevesBranchModel;
use Session;
use Validator;
use DataTables;
use Illuminate\Support\Facades\DB;

use Illuminate\Validation\Rule;

class CashiersReport_Payment_Controller extends Controller
{
	
	public function save_cash_payment_cashiers_report_p8(Request $request){	

			$CashiersReportId 		= $request->CashiersReportId;
			$CRPH8_ID 				= $request->CRPH8_ID;
								
								if($CRPH8_ID=='' || $CRPH8_ID ==0){	
								
									$CashiersReportModel_P8 = new CashiersReportModel_P8();
									
									$CashiersReportModel_P8->user_idx 						= Session::get('loginID');
									$CashiersReportModel_P8->cashiers_report_idx 			= $CashiersReportId;
									$CashiersReportModel_P8->cash_payment_amount 			= $request->cash_payment_amount;
									$CashiersReportModel_P8->limitless_payment_amount 		= $request->limitless_payment_amount;
									$CashiersReportModel_P8->credit_debit_payment_amount 	= $request->credit_debit_payment_amount;
									$CashiersReportModel_P8->ewallet_payment_amount	 		= $request->ewallet_payment_amount;
									$CashiersReportModel_P8->created_by_user_id 			= Session::get('loginID');
						
									$result = $CashiersReportModel_P8->save();
									
									if($result){
										return response()->json(['success'=>'Cash Payment Successfully Created!']);
									}
									else{
										return response()->json(['success'=>'Error on Cash Payment']);
									}
									
								}else{
																	
									$CashiersReportModel_P8 = new CashiersReportModel_P8();
									$CashiersReportModel_P8 = CashiersReportModel_P8::find($CRPH8_ID);
									$CashiersReportModel_P8->cash_payment_amount 			= $request->cash_payment_amount;
									$CashiersReportModel_P8->limitless_payment_amount 		= $request->limitless_payment_amount;
									$CashiersReportModel_P8->credit_debit_payment_amount 	= $request->credit_debit_payment_amount;
									$CashiersReportModel_P8->ewallet_payment_amount	 		= $request->ewallet_payment_amount;
									$CashiersReportModel_P8->updated_by_user_id 			= Session::get('loginID');
									$result = $CashiersReportModel_P8->update();
									
									if($result){
										return response()->json(['success'=>'Cash Payment Successfully Updated!']);
									}
									else{
										return response()->json(['success'=>'Error on Cash Payment ']);
									}
									
								}
								
	}		
	
	/**/
	public function get_cash_payment_inventory_list(Request $request){		

			$data =  CashiersReportModel_P8::where('teves_cashiers_report_p8.cashiers_report_idx', $request->CashiersReportId)
					->orderBy('teves_cashiers_report_p8.cashiers_report_p8_id', 'asc')
					->get([
						'teves_cashiers_report_p8.cashiers_report_p8_id',
						'teves_cashiers_report_p8.cash_payment_amount',
						'teves_cashiers_report_p8.limitless_payment_amount',
						'teves_cashiers_report_p8.credit_debit_payment_amount',
						'teves_cashiers_report_p8.ewallet_payment_amount'
					]);
		
			return response()->json($data);			
	}

	public function cashiers_report_p8_info(Request $request){

		$CRPH8_ID 				= $request->CRPH8_ID;
		
		$data =  CashiersReportModel_P8::where('teves_cashiers_report_p8.cashiers_report_p8_id', $CRPH8_ID)
					->get([
						'teves_cashiers_report_p8.cash_payment_amount',
						'teves_cashiers_report_p8.limitless_payment_amount',
						'teves_cashiers_report_p8.credit_debit_payment_amount',
						'teves_cashiers_report_p8.ewallet_payment_amount' 
					]);
					
		return response()->json($data);
		
	}
	
	public function delete_cash_payment_report(Request $request){		
			
		$CRPH8_ID 				= $request->CRPH8_ID;
		CashiersReportModel_P8::find($CRPH8_ID)->delete();
		return 'Deleted';
		
	}
	
	
}
