<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\ProductModel;
use App\Models\TevesBranchModel;

use App\Models\CashiersReportModel;
use App\Models\CashiersReportModel_P1;
use App\Models\CashiersReportModel_P3;
use App\Models\CashiersReportModel_P2;
use App\Models\CashiersReportModel_P8;

class ProcessCashierReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
{
    $yesterday = Carbon::now()->subDay();

    $reports = DB::table('teves_cashiers_report')
        ->where('updated_at', '<=', $yesterday)
        ->get();

    foreach ($reports as $report) {

        $company_header = $report->teves_branch;

        // you can reuse your logic here
        // BUT instead of looping date range, use report_date directly

        $date_only = Carbon::parse($report->report_date)->format('Y-m-d');

        $hourly_start = Carbon::parse($date_only)->subMinutes(5);
        $hourly_end   = Carbon::parse($date_only)->addMinutes(1435);

        // 👉 paste your computations here (cash, fuel, etc.)
        // from your controller

		/*Disable SQL Violation*/
			\DB::statement("SET SQL_MODE=''");
			
		/*Raw*/
				/*Start - Cash on Hand*/	
				/*		
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
					
				 $daily_cash_transaction_data = DB::select("$raw_query_cash_tansaction", [$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end,
											$company_header,$hourly_start,$hourly_end]
											);
				*/
				
			$cash_transactions = DB::table('teves_cashiers_report_p5')
			->join('teves_cashiers_report', 'teves_cashiers_report.cashiers_report_id', '=', 'teves_cashiers_report_p5.cashiers_report_id')
			->where('teves_cashiers_report.teves_branch', $company_header)
			->whereBetween('teves_cashiers_report.report_date', [$hourly_start, $hourly_end])
			->selectRaw("
				teves_cashiers_report.shift,
				SUM(
					one_thousand_deno*1000 +
					five_hundred_deno*500 +
					two_hundred_deno*200 +
					one_hundred_deno*100 +
					fifty_deno*50 +
					twenty_deno*20 +
					ten_deno*10 +
					five_deno*5 +
					one_deno*1 +
					twenty_five_cent_deno*0.25 +
					cash_drop
				) as total
			")
			->groupBy('teves_cashiers_report.shift')
			->pluck('total', 'shift');
	
			$first_shift_total_cash = $cash_transactions['1st Shift'] ?? 0;
			$second_shift_total_cash = $cash_transactions['2nd Shift'] ?? 0;
			$third_shift_total_cash = $cash_transactions['3rd Shift'] ?? 0;
			$fourth_shift_total_cash = $cash_transactions['4th Shift'] ?? 0;
			$fifth_shift_total_cash = $cash_transactions['5th Shift'] ?? 0;
			$sixth_shift_total_cash = $cash_transactions['6th Shift'] ?? 0;
				
				 $shift_cash_tansaction_sum = $first_shift_total_cash + 
				 $second_shift_total_cash +
				 $third_shift_total_cash + 
				 $fourth_shift_total_cash + 
				 $fifth_shift_total_cash + 
				 $sixth_shift_total_cash;
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
			
			/*Non-Cash*/
			$total_non_cash_per_shift = CashiersReportModel_P8::join(
					'teves_cashiers_report',
					'teves_cashiers_report.cashiers_report_id',
					'=',
					'teves_cashiers_report_p8.cashiers_report_idx'
				)
				->where('teves_branch', $company_header)
				->whereBetween('teves_cashiers_report.report_date', [$hourly_start, $hourly_end])
				->groupBy('shift')
				->selectRaw('shift, SUM(payment_amount) as total')
				->pluck('total', 'shift');

			$total_non_cash_payment_1st_shift = $total_non_cash_per_shift['1st Shift'] ?? 0;
			$total_non_cash_payment_2nd_shift = $total_non_cash_per_shift['2nd Shift'] ?? 0;
			$total_non_cash_payment_3rd_shift = $total_non_cash_per_shift['3rd Shift'] ?? 0;
			$total_non_cash_payment_4th_shift = $total_non_cash_per_shift['4th Shift'] ?? 0;
			$total_non_cash_payment_5th_shift = $total_non_cash_per_shift['5th Shift'] ?? 0;
			$total_non_cash_payment_6th_shift = $total_non_cash_per_shift['6th Shift'] ?? 0;
		
			$total_non_cash_payment = $total_non_cash_payment_1st_shift + $total_non_cash_payment_2nd_shift + $total_non_cash_payment_3rd_shift + $total_non_cash_payment_4th_shift + $total_non_cash_payment_5th_shift + $total_non_cash_payment_6th_shift;
		
			/*Non Cash Payment*/
			/*
			$first_shift_total_cash = $cash_transactions['1st Shift'] ?? 0;
			$second_shift_total_cash = $cash_transactions['2nd Shift'] ?? 0;
			$third_shift_total_cash = $cash_transactions['3rd Shift'] ?? 0;
			$fourth_shift_total_cash = $cash_transactions['4th Shift'] ?? 0;
			$fifth_shift_total_cash = $cash_transactions['5th Shift'] ?? 0;
			$sixth_shift_total_cash = $cash_transactions['6th Shift'] ?? 0;
			*/
			/*Total Sales Per Shift*/
			$first_shift_total_sales	 =  $first_shift_total_cash + $total_non_cash_payment_1st_shift;
			$second_shift_total_sales	 =  $second_shift_total_cash + $total_non_cash_payment_2nd_shift;
			$third_shift_total_sales	 =  $third_shift_total_cash + $total_non_cash_payment_3rd_shift;
			$fourth_shift_total_sales	 =  $fourth_shift_total_cash + $total_non_cash_payment_4th_shift;
			$fifth_shift_total_sales	 =  $fifth_shift_total_cash + $total_non_cash_payment_5th_shift;
			$sixth_shift_total_sales	 =  $sixth_shift_total_cash + $total_non_cash_payment_6th_shift;
			
			/*Short/Over*/
			$daily_fuel_sales			 = $total_fuel_sales;		/*Phase 1*/
       		$daily_non_cash_payment		 = $total_non_cash_payment;	/*Phase 8*/
			
			$daily_other_sales	 		 = $total_other_sales;		/*Phase 2*/

			$daily_sales_order = $shift_total_sales_sum;/*PH3*/
			$daily_discount = $shift_total_discounts_sum;/*PH3*/
			$daily_cashout_other = $shift_total_cashout_other_sum;/*PH3*/

			$daily_miscellaneous_items 	 = $shift_total_sales_sum + $shift_total_discounts_sum + $shift_total_cashout_other_sum;	/*Phase 3*/
			
			$daily_theoretical_sales = ($daily_fuel_sales + $daily_other_sales) - ($daily_miscellaneous_items);
			
			$daily_cash_transaction = $shift_cash_tansaction_sum;
			$daily_short_over = ($daily_cash_transaction + $daily_non_cash_payment) - $daily_theoretical_sales;
			
			$daily_total_cash_sales = $daily_non_cash_payment + $daily_cash_transaction;
			
					$result[] = array(
					 'date' 					=>  $date_only,
					 'branch_idx' 				=>  $date_only,
					 'first_shift_total_sales'	=> 	$first_shift_total_sales,
					 'second_shift_total_sales' => 	$second_shift_total_sales,
					 'third_shift_total_sales' 	=> 	$third_shift_total_sales,
					 'fourth_shift_total_sales' => 	$fourth_shift_total_sales,
					 'fifth_shift_total_sales' 	=> 	$fifth_shift_total_sales,
					 'sixth_shift_total_sales' 	=> 	$sixth_shift_total_sales,
					 'shift_total_sales_sum' 	=> 	$shift_total_sales_sum,
					 'daily_short_over' 		=> 	$daily_short_over,
					 'daily_other_sales' 		=> 	$daily_other_sales,
					 'daily_cash_transaction' 	=> 	$daily_cash_transaction,
					 'daily_fuel_sales' 		=> 	$daily_fuel_sales,
					 'daily_discount' 			=> 	$daily_discount,
					 'daily_cashout_other' 		=> 	$daily_cashout_other,
					 'daily_theoretical_sales' 	=>  $daily_theoretical_sales,
					 'daily_non_cash_payment'	=> 	$daily_non_cash_payment,
					 'daily_total_cash_sales'	=>	$daily_total_cash_sales
					 );


			DB::table('teves_cashier_daily_reports')->updateOrInsert(
				[
					'date'       => $date_only,
					'branch_idx' => $company_header
				],
				[
					'first_shift_total_sales'  => $first_shift_total_sales,
					'second_shift_total_sales' => $second_shift_total_sales,
					'third_shift_total_sales'  => $third_shift_total_sales,
					'fourth_shift_total_sales'=> $fourth_shift_total_sales,
					'fifth_shift_total_sales'  => $fifth_shift_total_sales,
					'sixth_shift_total_sales'  => $sixth_shift_total_sales,
					'shift_total_sales_sum'    => $shift_total_sales_sum,
					'daily_short_over'         => $daily_short_over,
					'daily_other_sales'        => $daily_other_sales,
					'daily_cash_transaction'   => $daily_cash_transaction,
					'daily_fuel_sales'         => $daily_fuel_sales,
					'daily_discount'           => $daily_discount,
					'daily_cashout_other'      => $daily_cashout_other,
					'daily_theoretical_sales'  => $daily_theoretical_sales,
					'daily_non_cash_payment'   => $daily_non_cash_payment,
					'daily_total_cash_sales'   => $daily_total_cash_sales,
					'updated_at'               => now(),
					'created_at'               => now(),
				]
			);
			
    }
}
}
