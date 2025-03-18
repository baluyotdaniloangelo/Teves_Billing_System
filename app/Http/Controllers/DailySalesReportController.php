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
use App\Models\CashiersReportModel_P1;
use App\Models\CashiersReportModel_P3;
use App\Models\CashiersReportModel_P2;
use App\Models\CashiersReportModel_P8;

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
				/*Start - Cash on Hand*/	 
				$raw_query_cash_tansaction = "SELECT  
					(select
					ifnull(sum(
					teves_cashiers_report_p5.one_thousand_deno*1000+
					teves_cashiers_report_p5.five_hundred_deno*500+
					teves_cashiers_report_p5.two_hundred_deno*200+
					teves_cashiers_report_p5.one_hundred_deno*100+
					teves_cashiers_report_p5.fifty_deno*50+
					teves_cashiers_report_p5.twenty_deno*20+
					teves_cashiers_report_p5.ten_deno*10+
					teves_cashiers_report_p5.five_deno*5+
					teves_cashiers_report_p5.one_deno*1+
					teves_cashiers_report_p5.twenty_five_cent_deno*0.25+
					teves_cashiers_report_p5.cash_drop
					),0) from `teves_cashiers_report_p5`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p5`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report`.`shift` = '1st Shift'
					) AS first_shift_total_cash_tansaction,
					 
					(select
					ifnull(sum(
					teves_cashiers_report_p5.one_thousand_deno*1000+
					teves_cashiers_report_p5.five_hundred_deno*500+
					teves_cashiers_report_p5.two_hundred_deno*200+
					teves_cashiers_report_p5.one_hundred_deno*100+
					teves_cashiers_report_p5.fifty_deno*50+
					teves_cashiers_report_p5.twenty_deno*20+
					teves_cashiers_report_p5.ten_deno*10+
					teves_cashiers_report_p5.five_deno*5+
					teves_cashiers_report_p5.one_deno*1+
					teves_cashiers_report_p5.twenty_five_cent_deno*0.25+
					teves_cashiers_report_p5.cash_drop
					),0) from `teves_cashiers_report_p5`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p5`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report`.`shift` = '2nd Shift'
					) AS second_shift_total_cash_tansaction,
					  
					(select
					ifnull(sum(
					teves_cashiers_report_p5.one_thousand_deno*1000+
					teves_cashiers_report_p5.five_hundred_deno*500+
					teves_cashiers_report_p5.two_hundred_deno*200+
					teves_cashiers_report_p5.one_hundred_deno*100+
					teves_cashiers_report_p5.fifty_deno*50+
					teves_cashiers_report_p5.twenty_deno*20+
					teves_cashiers_report_p5.ten_deno*10+
					teves_cashiers_report_p5.five_deno*5+
					teves_cashiers_report_p5.one_deno*1+
					teves_cashiers_report_p5.twenty_five_cent_deno*0.25+
					teves_cashiers_report_p5.cash_drop
					),0) from `teves_cashiers_report_p5`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p5`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report`.`shift` = '3rd Shift'
					) AS third_shift_total_cash_tansaction,
					
					(select
					ifnull(sum(
					teves_cashiers_report_p5.one_thousand_deno*1000+
					teves_cashiers_report_p5.five_hundred_deno*500+
					teves_cashiers_report_p5.two_hundred_deno*200+
					teves_cashiers_report_p5.one_hundred_deno*100+
					teves_cashiers_report_p5.fifty_deno*50+
					teves_cashiers_report_p5.twenty_deno*20+
					teves_cashiers_report_p5.ten_deno*10+
					teves_cashiers_report_p5.five_deno*5+
					teves_cashiers_report_p5.one_deno*1+
					teves_cashiers_report_p5.twenty_five_cent_deno*0.25+
					teves_cashiers_report_p5.cash_drop
					),0) from `teves_cashiers_report_p5`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p5`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report`.`shift` = '4th Shift'
					) AS fourth_shift_total_cash_tansaction,
					
					(select
					ifnull(sum(
					teves_cashiers_report_p5.one_thousand_deno*1000+
					teves_cashiers_report_p5.five_hundred_deno*500+
					teves_cashiers_report_p5.two_hundred_deno*200+
					teves_cashiers_report_p5.one_hundred_deno*100+
					teves_cashiers_report_p5.fifty_deno*50+
					teves_cashiers_report_p5.twenty_deno*20+
					teves_cashiers_report_p5.ten_deno*10+
					teves_cashiers_report_p5.five_deno*5+
					teves_cashiers_report_p5.one_deno*1+
					teves_cashiers_report_p5.twenty_five_cent_deno*0.25+
					teves_cashiers_report_p5.cash_drop
					),0) from `teves_cashiers_report_p5`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p5`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report`.`shift` = '5th Shift'
					) AS fifth_shift_total_cash_tansaction,
					
					(select
					ifnull(sum(
					teves_cashiers_report_p5.one_thousand_deno*1000+
					teves_cashiers_report_p5.five_hundred_deno*500+
					teves_cashiers_report_p5.two_hundred_deno*200+
					teves_cashiers_report_p5.one_hundred_deno*100+
					teves_cashiers_report_p5.fifty_deno*50+
					teves_cashiers_report_p5.twenty_deno*20+
					teves_cashiers_report_p5.ten_deno*10+
					teves_cashiers_report_p5.five_deno*5+
					teves_cashiers_report_p5.one_deno*1+
					teves_cashiers_report_p5.twenty_five_cent_deno*0.25+
					teves_cashiers_report_p5.cash_drop
					),0) from `teves_cashiers_report_p5`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p5`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report`.`shift` = '6th Shift'
					) AS sixth_shift_total_cash_tansaction";
					
				 $daily_cash_tansaction_data = DB::select("$raw_query_cash_tansaction", [$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end]
											);
				
				 $shift_cash_tansaction_sum = $daily_cash_tansaction_data[0]->first_shift_total_cash_tansaction + 
				 $daily_cash_tansaction_data[0]->second_shift_total_cash_tansaction +
				 $daily_cash_tansaction_data[0]->third_shift_total_cash_tansaction + 
				 $daily_cash_tansaction_data[0]->fourth_shift_total_cash_tansaction + 
				 $daily_cash_tansaction_data[0]->fifth_shift_total_cash_tansaction + 
				 $daily_cash_tansaction_data[0]->sixth_shift_total_cash_tansaction;
				 /* End - Cash On Hand*/
			
				/*Start - Other Sales*/
				$total_other_sales_1st_shift =  CashiersReportModel_P2::where('shift','1st Shift')
				->where('teves_branch',$company_header)
				->whereBetween('teves_cashiers_report.report_date', ["$hourly_start", "$hourly_end"])
				->join('teves_cashiers_report', 'teves_cashiers_report.cashiers_report_id', '=', 'teves_cashiers_report_p2.cashiers_report_id')
				->sum('order_total_amount');
				
				$total_other_sales_2nd_shift =  CashiersReportModel_P2::where('shift','2nd Shift')
				->where('teves_branch',$company_header)
				->whereBetween('teves_cashiers_report.report_date', ["$hourly_start", "$hourly_end"])
				->join('teves_cashiers_report', 'teves_cashiers_report.cashiers_report_id', '=', 'teves_cashiers_report_p2.cashiers_report_id')
				->sum('order_total_amount');
				
				$total_other_sales_3rd_shift =  CashiersReportModel_P2::where('shift','3rd Shift')
				->where('teves_branch',$company_header)
				->whereBetween('teves_cashiers_report.report_date', ["$hourly_start", "$hourly_end"])
				->join('teves_cashiers_report', 'teves_cashiers_report.cashiers_report_id', '=', 'teves_cashiers_report_p2.cashiers_report_id')
				->sum('order_total_amount');
				
				$total_other_sales_4th_shift =  CashiersReportModel_P2::where('shift','4th Shift')
				->where('teves_branch',$company_header)
				->whereBetween('teves_cashiers_report.report_date', ["$hourly_start", "$hourly_end"])
				->join('teves_cashiers_report', 'teves_cashiers_report.cashiers_report_id', '=', 'teves_cashiers_report_p2.cashiers_report_id')
				->sum('order_total_amount');
				
				$total_other_sales_5th_shift =  CashiersReportModel_P2::where('shift','5th Shift')
				->where('teves_branch',$company_header)
				->whereBetween('teves_cashiers_report.report_date', ["$hourly_start", "$hourly_end"])
				->join('teves_cashiers_report', 'teves_cashiers_report.cashiers_report_id', '=', 'teves_cashiers_report_p2.cashiers_report_id')
				->sum('order_total_amount');
				
				$total_other_sales_6th_shift =  CashiersReportModel_P2::where('shift','6th Shift')
				->where('teves_branch',$company_header)
				->whereBetween('teves_cashiers_report.report_date', ["$hourly_start", "$hourly_end"])
				->join('teves_cashiers_report', 'teves_cashiers_report.cashiers_report_id', '=', 'teves_cashiers_report_p2.cashiers_report_id')
				->sum('order_total_amount');
				
				$total_other_sales = $total_other_sales_1st_shift + $total_other_sales_2nd_shift + $total_other_sales_3rd_shift + $total_other_sales_4th_shift + $total_other_sales_5th_shift + $total_other_sales_6th_shift;
				/*End - Other Sales*/
				
				
				/*SALES ORDER - CREDIT SALES - RETAIL*/
				$raw_query_sales_order = "SELECT  
					(select
					ifnull(sum(teves_cashiers_report_p3.order_total_amount),0) from `teves_cashiers_report_p3`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p3`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report_p3`.`miscellaneous_items_type` = 'SALES_CREDIT' AND
					`teves_cashiers_report`.`shift` = '1st Shift' order by `cashiers_report_p3_id` asc 
					) AS first_shift_sales,
					(select
					ifnull(sum(teves_cashiers_report_p3.order_total_amount),0) from `teves_cashiers_report_p3`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p3`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report_p3`.`miscellaneous_items_type` = 'SALES_CREDIT' AND
					`teves_cashiers_report`.`shift` = '2nd Shift' order by `cashiers_report_p3_id` asc 
					) AS second_shift_sales,
					(select
					ifnull(sum(teves_cashiers_report_p3.order_total_amount),0) from `teves_cashiers_report_p3`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p3`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report_p3`.`miscellaneous_items_type` = 'SALES_CREDIT' AND
					`teves_cashiers_report`.`shift` = '3rd Shift' order by `cashiers_report_p3_id` asc 
					) AS third_shift_sales,
					(select
					ifnull(sum(teves_cashiers_report_p3.order_total_amount),0) from `teves_cashiers_report_p3`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p3`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report_p3`.`miscellaneous_items_type` = 'SALES_CREDIT' AND
					`teves_cashiers_report`.`shift` = '4th Shift' order by `cashiers_report_p3_id` asc 
					) AS fourth_shift_sales,
					(select
					ifnull(sum(teves_cashiers_report_p3.order_total_amount),0) from `teves_cashiers_report_p3`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p3`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report_p3`.`miscellaneous_items_type` = 'SALES_CREDIT' AND
					`teves_cashiers_report`.`shift` = '5th Shift' order by `cashiers_report_p3_id` asc 
					) AS fifth_shift_sales,
					(select
					ifnull(sum(teves_cashiers_report_p3.order_total_amount),0) from `teves_cashiers_report_p3`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p3`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report_p3`.`miscellaneous_items_type` = 'SALES_CREDIT' AND
					`teves_cashiers_report`.`shift` = '6th Shift' order by `cashiers_report_p3_id` asc 
					) AS sixth_shift_sales";	
					
				$daily_sales_data = DB::select("$raw_query_sales_order", [$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end]
											);
				
				 $shift_total_sales_sum = $daily_sales_data[0]->first_shift_sales + 
				 $daily_sales_data[0]->second_shift_sales +
				 $daily_sales_data[0]->third_shift_sales + 
				 $daily_sales_data[0]->fourth_shift_sales + 
				 $daily_sales_data[0]->fifth_shift_sales + 
				 $daily_sales_data[0]->sixth_shift_sales;
				 /*SALES ORDER - CREDIT SALES - RETAIL*/
				 
				 
				/*DISCOUNTS*/
				$raw_query_DISCOUNTS = "SELECT  
					(select
					ifnull(sum(teves_cashiers_report_p3.order_total_amount),0) from `teves_cashiers_report_p3`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p3`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report_p3`.`miscellaneous_items_type` = 'DISCOUNTS' AND
					`teves_cashiers_report`.`shift` = '1st Shift' order by `cashiers_report_p3_id` asc 
					) AS first_shift_discounts,
					(select
					ifnull(sum(teves_cashiers_report_p3.order_total_amount),0) from `teves_cashiers_report_p3`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p3`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report_p3`.`miscellaneous_items_type` = 'DISCOUNTS' AND
					`teves_cashiers_report`.`shift` = '2nd Shift' order by `cashiers_report_p3_id` asc 
					) AS second_shift_discounts,
					(select
					ifnull(sum(teves_cashiers_report_p3.order_total_amount),0) from `teves_cashiers_report_p3`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p3`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report_p3`.`miscellaneous_items_type` = 'DISCOUNTS' AND
					`teves_cashiers_report`.`shift` = '3rd Shift' order by `cashiers_report_p3_id` asc 
					) AS third_shift_discounts,
					(select
					ifnull(sum(teves_cashiers_report_p3.order_total_amount),0) from `teves_cashiers_report_p3`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p3`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report_p3`.`miscellaneous_items_type` = 'DISCOUNTS' AND
					`teves_cashiers_report`.`shift` = '4th Shift' order by `cashiers_report_p3_id` asc 
					) AS fourth_shift_discounts,
					(select
					ifnull(sum(teves_cashiers_report_p3.order_total_amount),0) from `teves_cashiers_report_p3`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p3`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report_p3`.`miscellaneous_items_type` = 'DISCOUNTS' AND
					`teves_cashiers_report`.`shift` = '5th Shift' order by `cashiers_report_p3_id` asc 
					) AS fifth_shift_discounts,
					(select
					ifnull(sum(teves_cashiers_report_p3.order_total_amount),0) from `teves_cashiers_report_p3`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p3`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report_p3`.`miscellaneous_items_type` = 'DISCOUNTS' AND
					`teves_cashiers_report`.`shift` = '6th Shift' order by `cashiers_report_p3_id` asc 
					) AS sixth_shift_discounts";	
					
				$daily_discounts_data = DB::select("$raw_query_DISCOUNTS", [$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end]
											);
				
				 $shift_total_discounts_sum = $daily_discounts_data[0]->first_shift_discounts + 
				 $daily_discounts_data[0]->second_shift_discounts +
				 $daily_discounts_data[0]->third_shift_discounts + 
				 $daily_discounts_data[0]->fourth_shift_discounts + 
				 $daily_discounts_data[0]->fifth_shift_discounts + 
				 $daily_discounts_data[0]->sixth_shift_discounts;
				 /*DISCOUNTS*/				 
				
				 /*CASH OUT AND OTHER*/
					$raw_query_CASHOUT_OTHER = "SELECT  
					(select
					ifnull(sum(teves_cashiers_report_p3.order_total_amount),0) from `teves_cashiers_report_p3`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p3`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report_p3`.`miscellaneous_items_type` in ('CASHOUT', 'OTHERS') AND
					`teves_cashiers_report`.`shift` = '1st Shift' order by `cashiers_report_p3_id` asc 
					) AS first_shift_discounts,
					(select
					ifnull(sum(teves_cashiers_report_p3.order_total_amount),0) from `teves_cashiers_report_p3`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p3`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report_p3`.`miscellaneous_items_type` in ('CASHOUT', 'OTHERS') AND
					`teves_cashiers_report`.`shift` = '2nd Shift' order by `cashiers_report_p3_id` asc 
					) AS second_shift_discounts,
					(select
					ifnull(sum(teves_cashiers_report_p3.order_total_amount),0) from `teves_cashiers_report_p3`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p3`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report_p3`.`miscellaneous_items_type` in ('CASHOUT', 'OTHERS') AND
					`teves_cashiers_report`.`shift` = '3rd Shift' order by `cashiers_report_p3_id` asc 
					) AS third_shift_discounts,
					(select
					ifnull(sum(teves_cashiers_report_p3.order_total_amount),0) from `teves_cashiers_report_p3`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p3`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report_p3`.`miscellaneous_items_type` in ('CASHOUT', 'OTHERS') AND
					`teves_cashiers_report`.`shift` = '4th Shift' order by `cashiers_report_p3_id` asc 
					) AS fourth_shift_discounts,
					(select
					ifnull(sum(teves_cashiers_report_p3.order_total_amount),0) from `teves_cashiers_report_p3`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p3`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report_p3`.`miscellaneous_items_type` in ('CASHOUT', 'OTHERS') AND
					`teves_cashiers_report`.`shift` = '5th Shift' order by `cashiers_report_p3_id` asc 
					) AS fifth_shift_discounts,
					(select
					ifnull(sum(teves_cashiers_report_p3.order_total_amount),0) from `teves_cashiers_report_p3`
					join `teves_cashiers_report` ON `teves_cashiers_report`.`cashiers_report_id` = `teves_cashiers_report_p3`.`cashiers_report_id` 
					WHERE 
					`teves_cashiers_report`.`teves_branch` = ? AND
					`teves_cashiers_report`.`report_date` >= ? and
					`teves_cashiers_report`.`report_date` <= ? and
					`teves_cashiers_report_p3`.`miscellaneous_items_type` in ('CASHOUT', 'OTHERS') AND
					`teves_cashiers_report`.`shift` = '6th Shift' order by `cashiers_report_p3_id` asc 
					) AS sixth_shift_discounts";	
					
				$daily_cashout_other_data = DB::select("$raw_query_CASHOUT_OTHER", [$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end]
											);
				
				$shift_total_cashout_other_sum = $daily_cashout_other_data[0]->first_shift_discounts + 
				 $daily_cashout_other_data[0]->second_shift_discounts +
				 $daily_cashout_other_data[0]->third_shift_discounts + 
				 $daily_cashout_other_data[0]->fourth_shift_discounts + 
				 $daily_cashout_other_data[0]->fifth_shift_discounts + 
				 $daily_cashout_other_data[0]->sixth_shift_discounts;		 
				 /*CASH OUT AND OTHER*/
				  

				

			/*Start - Fuel Sales*/
			$fuel_ids = [11,12,13];
			
			$total_fuel_sales_1st_shift =  CashiersReportModel_P1::where('shift','1st Shift')
			->where('teves_branch',$company_header)
			->whereBetween('teves_cashiers_report.report_date', ["$hourly_start", "$hourly_end"])
			->whereIn('product_idx', $fuel_ids)
			->join('teves_cashiers_report', 'teves_cashiers_report.cashiers_report_id', '=', 'teves_cashiers_report_p1.cashiers_report_id')
			->sum('order_total_amount'); 
						
			$total_fuel_sales_2nd_shift =  CashiersReportModel_P1::where('shift','2nd Shift')
			->where('teves_branch',$company_header)
			->whereBetween('teves_cashiers_report.report_date', ["$hourly_start", "$hourly_end"])
			->whereIn('product_idx', $fuel_ids)
			->join('teves_cashiers_report', 'teves_cashiers_report.cashiers_report_id', '=', 'teves_cashiers_report_p1.cashiers_report_id')
			->sum('order_total_amount'); 
			
			$total_fuel_sales_3rd_shift =  CashiersReportModel_P1::where('shift','3rd Shift')
			->where('teves_branch',$company_header)
			->whereBetween('teves_cashiers_report.report_date', ["$hourly_start", "$hourly_end"])
			->whereIn('product_idx', $fuel_ids)
			->join('teves_cashiers_report', 'teves_cashiers_report.cashiers_report_id', '=', 'teves_cashiers_report_p1.cashiers_report_id')
			->sum('order_total_amount'); 
			
			$total_fuel_sales_4th_shift =  CashiersReportModel_P1::where('shift','4th Shift')
			->where('teves_branch',$company_header)
			->whereBetween('teves_cashiers_report.report_date', ["$hourly_start", "$hourly_end"])
			->whereIn('product_idx', $fuel_ids)
			->join('teves_cashiers_report', 'teves_cashiers_report.cashiers_report_id', '=', 'teves_cashiers_report_p1.cashiers_report_id')
			->sum('order_total_amount');

			$total_fuel_sales_5th_shift =  CashiersReportModel_P1::where('shift','5th Shift')
			->where('teves_branch',$company_header)
			->whereBetween('teves_cashiers_report.report_date', ["$hourly_start", "$hourly_end"])
			->whereIn('product_idx', $fuel_ids)
			->join('teves_cashiers_report', 'teves_cashiers_report.cashiers_report_id', '=', 'teves_cashiers_report_p1.cashiers_report_id')
			->sum('order_total_amount'); 

			$total_fuel_sales_6th_shift =  CashiersReportModel_P1::where('shift','6th Shift')
			->where('teves_branch',$company_header)
			->whereBetween('teves_cashiers_report.report_date', ["$hourly_start", "$hourly_end"])
			->whereIn('product_idx', $fuel_ids)
			->join('teves_cashiers_report', 'teves_cashiers_report.cashiers_report_id', '=', 'teves_cashiers_report_p1.cashiers_report_id')
			->sum('order_total_amount'); 	
					
			$total_fuel_sales = $total_fuel_sales_1st_shift+$total_fuel_sales_2nd_shift+$total_fuel_sales_3rd_shift+$total_fuel_sales_4th_shift+$total_fuel_sales_5th_shift+$total_fuel_sales_6th_shift;
			/*Start - Fuel Sales*/
			
			
			/*Non Cash Payment*/
			$total_non_cash_payment_1st_shift =  CashiersReportModel_P8::where('shift','1st Shift')
			->where('teves_branch',$company_header)
			->whereBetween('teves_cashiers_report.report_date', ["$hourly_start", "$hourly_end"])
			->join('teves_cashiers_report', 'teves_cashiers_report.cashiers_report_id', '=', 'teves_cashiers_report_p8.cashiers_report_idx')
			->sum(\DB::raw('online_payment_amount + limitless_payment_amount + credit_debit_payment_amount + gcash_payment_amount'));
			
			$total_non_cash_payment_2nd_shift =  CashiersReportModel_P8::where('shift','2nd Shift')
			->where('teves_branch',$company_header)
			->whereBetween('teves_cashiers_report.report_date', ["$hourly_start", "$hourly_end"])
			->join('teves_cashiers_report', 'teves_cashiers_report.cashiers_report_id', '=', 'teves_cashiers_report_p8.cashiers_report_idx')
			->sum(\DB::raw('online_payment_amount + limitless_payment_amount + credit_debit_payment_amount + gcash_payment_amount'));
			
			$total_non_cash_payment_3rd_shift =  CashiersReportModel_P8::where('shift','3rd Shift')
			->where('teves_branch',$company_header)
			->whereBetween('teves_cashiers_report.report_date', ["$hourly_start", "$hourly_end"])
			->join('teves_cashiers_report', 'teves_cashiers_report.cashiers_report_id', '=', 'teves_cashiers_report_p8.cashiers_report_idx')
			->sum(\DB::raw('online_payment_amount + limitless_payment_amount + credit_debit_payment_amount + gcash_payment_amount'));
			
			$total_non_cash_payment_4th_shift =  CashiersReportModel_P8::where('shift','4th Shift')
			->where('teves_branch',$company_header)
			->whereBetween('teves_cashiers_report.report_date', ["$hourly_start", "$hourly_end"])
			->join('teves_cashiers_report', 'teves_cashiers_report.cashiers_report_id', '=', 'teves_cashiers_report_p8.cashiers_report_idx')
			->sum(\DB::raw('online_payment_amount + limitless_payment_amount + credit_debit_payment_amount + gcash_payment_amount'));
			
			$total_non_cash_payment_5th_shift =  CashiersReportModel_P8::where('shift','5th Shift')
			->where('teves_branch',$company_header)
			->whereBetween('teves_cashiers_report.report_date', ["$hourly_start", "$hourly_end"])
			->join('teves_cashiers_report', 'teves_cashiers_report.cashiers_report_id', '=', 'teves_cashiers_report_p8.cashiers_report_idx')
			->sum(\DB::raw('online_payment_amount + limitless_payment_amount + credit_debit_payment_amount + gcash_payment_amount'));
			
			$total_non_cash_payment_6th_shift =  CashiersReportModel_P8::where('shift','6th Shift')
			->where('teves_branch',$company_header)
			->whereBetween('teves_cashiers_report.report_date', ["$hourly_start", "$hourly_end"])
			->join('teves_cashiers_report', 'teves_cashiers_report.cashiers_report_id', '=', 'teves_cashiers_report_p8.cashiers_report_idx')
			->sum(\DB::raw('online_payment_amount + limitless_payment_amount + credit_debit_payment_amount + gcash_payment_amount'));	
		
			$total_non_cash_payment = $total_non_cash_payment_1st_shift + $total_non_cash_payment_2nd_shift + $total_non_cash_payment_3rd_shift + $total_non_cash_payment_4th_shift + $total_non_cash_payment_5th_shift + $total_non_cash_payment_6th_shift;
		
			/*Non Cash Payment*/
			
			/*Short/Over*/
			$daily_fuel_sales			 = $total_fuel_sales;		/*Phase 1*/
       		$daily_non_cash_payment		 = $total_non_cash_payment;	/*Phase 8*/
			
			$daily_other_sales	 		 = $total_other_sales;		/*Phase 2*/
			$daily_miscellaneous_items 	 = $shift_total_sales_sum + $shift_total_discounts_sum + $shift_total_cashout_other_sum;	/*Phase 3*/
			
			$daily_theoretical_sales = ($daily_fuel_sales + $daily_other_sales) - ($daily_miscellaneous_items);
			
			$daily_cash_tansaction = $shift_cash_tansaction_sum;
			$daily_short_over = ($daily_cash_tansaction + $daily_non_cash_payment) - $daily_theoretical_sales;
			
					$result[] = array(
					 'date' 					=>  $date_only,
					 'first_shift_total_sales'	=> 	$daily_sales_data[0]->first_shift_sales,
					 'second_shift_total_sales' => 	$daily_sales_data[0]->second_shift_sales,
					 'third_shift_total_sales' 	=> 	$daily_sales_data[0]->third_shift_sales,
					 'fourth_shift_total_sales' => 	$daily_sales_data[0]->fourth_shift_sales,
					 'fifth_shift_total_sales' 	=> 	$daily_sales_data[0]->fifth_shift_sales,
					 'sixth_shift_total_sales' 	=> 	$daily_sales_data[0]->sixth_shift_sales,
					 'shift_total_sales_sum' 	=> 	$shift_total_sales_sum,
					 'daily_short_over' 		=> 	$daily_short_over,
					 'daily_other_sales' 		=> 	$daily_other_sales,
					 'daily_cash_tansaction' 	=> 	$daily_cash_tansaction
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