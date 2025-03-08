<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
//use App\Models\BillingTransactionModel;
//use App\Models\SOBillingTransactionModel;
//use App\Models\ReceivablesModel;
//use App\Models\ReceivablesPaymentModel;
use App\Models\ProductModel;
use App\Models\TevesBranchModel;
//use App\Models\SalesOrderModel;
//use App\Models\SalesOrderComponentModel;
//use App\Models\SalesOrderPaymentModel;

//use App\Models\PurchaseOrderModel;
//use App\Models\PurchaseOrderComponentModel;
//use App\Models\PurchaseOrderPaymentModel;

use App\Models\CashiersReportModel;
use App\Models\CashiersReportModel_P3;

use App\Models\ClientModel;
use Session;
use Validator;
//use DataTables;
use Illuminate\Support\Facades\DB;
/*Excel*/
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

/*PDF*/
use PDF;
use DataTables;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DailySalesReportController extends Controller
{
	
	/*Load Billing History Report Interface*/
	public function daily_sales_page(){
		
		if(Session::has('loginID')){
			
			$title = 'Daily Sales';
			$data = array();
			if(Session::has('loginID')){
				
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
				
			}

			return view("pages.daily_sales", compact('data','title','teves_branch'));
		
		}
	}  	
	
	public function generate_daily_sales(Request $request){

		$request->validate([
		  'start_date'      		=> 'required',
		  'end_date'      			=> 'required'
        ], 
        [
			'start_date.required' 	=> 'Please select a Start Date',
			'end_date.required' 	=> 'Please select a End Date'
        ]
		);

		$start_date = $request->start_date;
		$end_date = $request->end_date;
		$company_header = $request->company_header;
							
		$period = CarbonPeriod::create("$start_date 00:00", '1 Day', "$end_date 00:00");		
		$result = array();
		foreach ($period as $key => $date) {
			if ($key) {
				//echo "\n";
			}
		
			$daily 	= $date->format('Y-m-d H:i:s');
			$hourly 	= $date->format('Y-m-d H:i:s');
			
			$date_only 	= $date->format('Y-m-d');
			
			$dt_from_w_time = $hourly;
			$date1=date_create($dt_from_w_time);
			date_sub($date1,date_interval_create_from_date_string("5 minutes"));
			$hourly_start = date_format($date1,"Y-m-d H:i:s");
			
			/*NEXT DATE MAX*/
			$dt_to_w_time = $hourly;
			$date2=date_create($dt_to_w_time);
			date_add($date2,date_interval_create_from_date_string("1435 minutes"));
			$hourly_end = date_format($date2,"Y-m-d H:i:s");
		
		/*Disable SQL Violation*/
			\DB::statement("SET SQL_MODE=''");
			
		/*Raw*/			
		
				$raw_query = "SELECT  
					(select
					ifnull(sum(teves_cashiers_report_p3.order_total_amount),0) from `teves_cashiers_report_p3`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p3`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report_p3`.`miscellaneous_items_type` = 'SALES_CREDIT' AND
					`teves_cashiers_report`.`shift` = '1st Shift' order by `cashiers_report_p3_id` asc 
					) AS first_shift,
					(select
					ifnull(sum(teves_cashiers_report_p3.order_total_amount),0) from `teves_cashiers_report_p3`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p3`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report_p3`.`miscellaneous_items_type` = 'SALES_CREDIT' AND
					`teves_cashiers_report`.`shift` = '2nd Shift' order by `cashiers_report_p3_id` asc 
					) AS second_shift,
					(select
					ifnull(sum(teves_cashiers_report_p3.order_total_amount),0) from `teves_cashiers_report_p3`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p3`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report_p3`.`miscellaneous_items_type` = 'SALES_CREDIT' AND
					`teves_cashiers_report`.`shift` = '3rd Shift' order by `cashiers_report_p3_id` asc 
					) AS third_shift,
					(select
					ifnull(sum(teves_cashiers_report_p3.order_total_amount),0) from `teves_cashiers_report_p3`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p3`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report_p3`.`miscellaneous_items_type` = 'SALES_CREDIT' AND
					`teves_cashiers_report`.`shift` = '4th Shift' order by `cashiers_report_p3_id` asc 
					) AS fourth_shift,
					(select
					ifnull(sum(teves_cashiers_report_p3.order_total_amount),0) from `teves_cashiers_report_p3`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p3`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report_p3`.`miscellaneous_items_type` = 'SALES_CREDIT' AND
					`teves_cashiers_report`.`shift` = '5th Shift' order by `cashiers_report_p3_id` asc 
					) AS fifth_shift,
					(select
					ifnull(sum(teves_cashiers_report_p3.order_total_amount),0) from `teves_cashiers_report_p3`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p3`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report_p3`.`miscellaneous_items_type` = 'SALES_CREDIT' AND
					`teves_cashiers_report`.`shift` = '6th Shift' order by `cashiers_report_p3_id` asc 
					) AS sixth_shift";	
							   
				$daily_data = DB::select("$raw_query", [$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end]
											);
				
				 $shift_total_sum = $daily_data[0]->first_shift + 
				 $daily_data[0]->second_shift +
				 $daily_data[0]->third_shift + 
				 $daily_data[0]->fourth_shift + 
				 $daily_data[0]->fifth_shift + 
				 $daily_data[0]->sixth_shift;
				
					$result[] = array(
					 'date' => $date_only,
					 'first_shift_total'	=> 	$daily_data[0]->first_shift,
					 'second_shift_total' 	=> 	$daily_data[0]->second_shift,
					 'third_shift_total' 	=> 	$daily_data[0]->third_shift,
					 'fourth_shift_total' 	=> 	$daily_data[0]->fourth_shift,
					 'fifth_shift_total' 	=> 	$daily_data[0]->fifth_shift,
					 'sixth_shift_total' 	=> 	$daily_data[0]->sixth_shift,
					 'shift_total_sum' 		=> 	$shift_total_sum
					 );

			}
			
			return DataTables::of($result)
				->addIndexColumn()
                ->make(true);	
		
	}	
	
	public function generate_daily_sales_report_pdf(Request $request){


		$request->validate([
		  'start_date'      		=> 'required',
		  'end_date'      			=> 'required'
        ], 
        [
			'start_date.required' 	=> 'Please select a Start Date',
			'end_date.required' 	=> 'Please select a End Date'
        ]
		);

		$start_date = $request->start_date;
		$end_date = $request->end_date;
		$company_header = $request->company_header;
							
		$period = CarbonPeriod::create("$start_date 00:00", '1 Day', "$end_date 00:00");		
		$result = array();
		foreach ($period as $key => $date) {
			if ($key) {
				//echo "\n";
			}
		
			$daily 	= $date->format('Y-m-d H:i:s');
			$hourly 	= $date->format('Y-m-d H:i:s');
			
			$date_only 	= $date->format('Y-m-d');
			
			$dt_from_w_time = $hourly;
			$date1=date_create($dt_from_w_time);
			date_sub($date1,date_interval_create_from_date_string("5 minutes"));
			$hourly_start = date_format($date1,"Y-m-d H:i:s");
			
			/*NEXT DATE MAX*/
			$dt_to_w_time = $hourly;
			$date2=date_create($dt_to_w_time);
			date_add($date2,date_interval_create_from_date_string("1435 minutes"));
			$hourly_end = date_format($date2,"Y-m-d H:i:s");
		
		/*Disable SQL Violation*/
			\DB::statement("SET SQL_MODE=''");
			
		/*Raw*/			
		
				$raw_query = "SELECT  
					(select
					ifnull(sum(teves_cashiers_report_p3.order_total_amount),0) from `teves_cashiers_report_p3`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p3`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report_p3`.`miscellaneous_items_type` = 'SALES_CREDIT' AND
					`teves_cashiers_report`.`shift` = '1st Shift' order by `cashiers_report_p3_id` asc 
					) AS first_shift,
					(select
					ifnull(sum(teves_cashiers_report_p3.order_total_amount),0) from `teves_cashiers_report_p3`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p3`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report_p3`.`miscellaneous_items_type` = 'SALES_CREDIT' AND
					`teves_cashiers_report`.`shift` = '2nd Shift' order by `cashiers_report_p3_id` asc 
					) AS second_shift,
					(select
					ifnull(sum(teves_cashiers_report_p3.order_total_amount),0) from `teves_cashiers_report_p3`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p3`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report_p3`.`miscellaneous_items_type` = 'SALES_CREDIT' AND
					`teves_cashiers_report`.`shift` = '3rd Shift' order by `cashiers_report_p3_id` asc 
					) AS third_shift,
					(select
					ifnull(sum(teves_cashiers_report_p3.order_total_amount),0) from `teves_cashiers_report_p3`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p3`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report_p3`.`miscellaneous_items_type` = 'SALES_CREDIT' AND
					`teves_cashiers_report`.`shift` = '4th Shift' order by `cashiers_report_p3_id` asc 
					) AS fourth_shift,
					(select
					ifnull(sum(teves_cashiers_report_p3.order_total_amount),0) from `teves_cashiers_report_p3`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p3`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report_p3`.`miscellaneous_items_type` = 'SALES_CREDIT' AND
					`teves_cashiers_report`.`shift` = '5th Shift' order by `cashiers_report_p3_id` asc 
					) AS fifth_shift,
					(select
					ifnull(sum(teves_cashiers_report_p3.order_total_amount),0) from `teves_cashiers_report_p3`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p3`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report_p3`.`miscellaneous_items_type` = 'SALES_CREDIT' AND
					`teves_cashiers_report`.`shift` = '6th Shift' order by `cashiers_report_p3_id` asc 
					) AS sixth_shift";	
							   
				$daily_data = DB::select("$raw_query", [$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end]
											);
				
				 $shift_total_sum = $daily_data[0]->first_shift + 
				 $daily_data[0]->second_shift +
				 $daily_data[0]->third_shift + 
				 $daily_data[0]->fourth_shift + 
				 $daily_data[0]->fifth_shift + 
				 $daily_data[0]->sixth_shift;
				
					$result[] = array(
					 'date' => $date_only,
					 'first_shift_total'	=> 	$daily_data[0]->first_shift,
					 'second_shift_total' 	=> 	$daily_data[0]->second_shift,
					 'third_shift_total' 	=> 	$daily_data[0]->third_shift,
					 'fourth_shift_total' 	=> 	$daily_data[0]->fourth_shift,
					 'fifth_shift_total' 	=> 	$daily_data[0]->fifth_shift,
					 'sixth_shift_total' 	=> 	$daily_data[0]->sixth_shift,
					 'shift_total_sum' 		=> 	$shift_total_sum
					 );

			}
			
			//return DataTables::of($result)
			//	->addIndexColumn()
            //    ->make(true);	

		$receivable_header = TevesBranchModel::find($company_header, ['branch_code','branch_name','branch_tin','branch_address','branch_contact_number','branch_owner','branch_owner_title','branch_logo']);

		/*USER INFO*/
		$user_data = User::where('user_id', '=', Session::get('loginID'))->first();
		
		$title = 'Branch - Daily Sales';
		  
        $pdf = PDF::loadView('printables.report_daily_sales_pdf', compact('title', 'result', 'user_data','receivable_header','start_date','end_date'));
		
		/*Download Directly*/
        //return $pdf->download($client_data['client_name'].".pdf");
		/*Stream for Saving/Printing*/
		$pdf->setPaper('A4', 'landscape');/*Set to Landscape*/
		return $pdf->stream($receivable_header['branch_code']."_DAILY_SALES.pdf");
		
		//}
	}		
	

}