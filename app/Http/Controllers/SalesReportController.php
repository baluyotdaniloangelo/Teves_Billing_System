<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ProductModel;
use App\Models\TevesBranchModel;

use App\Models\CashiersReportModel;
//use App\Models\CashiersReportModel_P1;
//use App\Models\CashiersReportModel_P3;
//use App\Models\CashiersReportModel_P2;
//use App\Models\CashiersReportModel_P8;
use App\Models\CashiersReportModel_P6;

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

class SalesReportController extends Controller
{
	
	/*Load Billing History Report Interface*/
	public function sales_report_page(){
		
		if(Session::has('loginID')){
			
			$title = 'Sales Report';
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

			return view("pages.sales_report", compact('data','title','teves_branch'));
		
		}
	}  	
	
	public function generate_sales_report(Request $request){

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
		$data = array();
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
		
				// Step 1: Build dynamic columns
				$columns = DB::table('teves_cashiers_report_p6 as r')
					->join('teves_product_tank_table as t', 't.tank_id', '=', 'r.tank_idx')
					->join('teves_product_table as p', 'p.product_id', '=', 'r.product_idx')
					->join('teves_cashiers_report as m', 'm.cashiers_report_id', '=', 'r.cashiers_report_idx')
					->whereBetween('m.report_date', ["$start_date", "$end_date"])
					->where('t.branch_idx', $company_header)
					->selectRaw("
						GROUP_CONCAT(
							DISTINCT CONCAT(
								'SUM(CASE WHEN p.product_name = ''',
								p.product_name,
								''' AND t.tank_name = ''',
								t.tank_name,
								''' THEN r.sales_in_liters ELSE 0 END) AS `',
								REPLACE(p.product_name, '`', ''),
								'_',
								REPLACE(t.tank_name, '`', ''),
								'`'
							)
						) as cols
					")
					->value('cols');

					// Step 2: Only run final query if columns are not empty
					if (!empty($columns)) {
						$sql = "
							SELECT m.report_date, $columns
							FROM teves_cashiers_report_p6 r
							JOIN teves_product_tank_table t ON t.tank_id = r.tank_idx
							JOIN teves_product_table p ON p.product_id = r.product_idx
							JOIN teves_cashiers_report m ON r.cashiers_report_idx = m.cashiers_report_id
							WHERE m.report_date BETWEEN ? AND ?
							  AND m.teves_branch = ?
							GROUP BY m.report_date
						";

						// You can now execute this query using DB::select or a query builder
						$data = DB::select(preg_replace('/\s+/', ' ', $sql), [$start_date, $end_date, $company_header]);
					} else {
						// Optionally handle the case where no columns were found
						$data = [];
					}
					
			}
			
			// Step 3: Return as DataTable
			return DataTables::of($data)
				->addIndexColumn()
                ->make(true);	
		
	}	
	
	public function generate_sales_report_pdf(Request $request){

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
		$data = array();
		
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
			
		/*Disable SQL Violation*/
			\DB::statement("SET SQL_MODE=''");
			
		/*Raw*/
		
				// Step 1: Build dynamic columns
				$columns = DB::table('teves_cashiers_report_p6 as r')
					->join('teves_product_tank_table as t', 't.tank_id', '=', 'r.tank_idx')
					->join('teves_product_table as p', 'p.product_id', '=', 'r.product_idx')
					->join('teves_cashiers_report as m', 'm.cashiers_report_id', '=', 'r.cashiers_report_idx')
					->whereBetween('m.report_date', ["$start_date", "$end_date"])
					->where('t.teves_branch', $company_header)
					->selectRaw("
						GROUP_CONCAT(
							DISTINCT CONCAT(
								'SUM(CASE WHEN p.product_name = ''',
								p.product_name,
								''' AND t.tank_name = ''',
								t.tank_name,
								''' THEN r.sales_in_liters ELSE 0 END) AS `',
								REPLACE(p.product_name, '`', ''),
								'_',
								REPLACE(t.tank_name, '`', ''),
								'`'
							)
						) as cols
					")
					->value('cols');

					// Step 2: Only run final query if columns are not empty
					if (!empty($columns)) {
						$sql = "
							SELECT m.report_date as 'Report Date', $columns
							FROM teves_cashiers_report_p6 r
							JOIN teves_product_tank_table t ON t.tank_id = r.tank_idx
							JOIN teves_product_table p ON p.product_id = r.product_idx
							JOIN teves_cashiers_report m ON r.cashiers_report_idx = m.cashiers_report_id
							WHERE m.report_date BETWEEN ? AND ?
							  AND m.teves_branch = ?
							GROUP BY m.report_date
						";

						// You can now execute this query using DB::select or a query builder
						$data = DB::select($sql, [$start_date, $end_date, $company_header]);
					} else {
						// Optionally handle the case where no columns were found
						$data = [];
					}

			}
			
			//var_dump($data);
			
		$receivable_header = TevesBranchModel::find($company_header, ['branch_code','branch_name','branch_tin','branch_address','branch_contact_number','branch_owner','branch_owner_title','branch_logo']);

		/*USER INFO*/
		$user_data = User::where('user_id', '=', Session::get('loginID'))->first();
		
		$title = 'Sales Report';
		  
        $pdf = PDF::loadView('printables.report_sales_report_pdf', compact('title', 'data', 'user_data','receivable_header','start_date','end_date'));
		
		/*Download Directly*/
        //return $pdf->download($client_data['client_name'].".pdf");
		/*Stream for Saving/Printing*/
		$pdf->setPaper('Legal', 'landscape');/*Set to Landscape*/
		return $pdf->stream($receivable_header['branch_code']."_SALES_REPORT.pdf");
		
		
	}		
	
	public function generate_cumulative_sales_report_pdf(Request $request){

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
		$data = array();
		
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
			
		/*Disable SQL Violation*/
			\DB::statement("SET SQL_MODE=''");
			
		/*Raw*/

						$data = DB::table('teves_cashiers_report_p6 as r')
						->join('teves_product_tank_table as t', 't.tank_id', '=', 'r.tank_idx')
						->join('teves_product_table as p', 'p.product_id', '=', 'r.product_idx')
						->join('teves_cashiers_report as m', 'r.cashiers_report_idx', '=', 'm.cashiers_report_id')
						->select(
							'm.report_date',
							'p.product_name',
							't.tank_name',
							'm.teves_branch',
							DB::raw('DAYNAME(m.report_date) as report_day'),
							DB::raw('SUM(r.beginning_inventory) as beginning_inventory'),
							DB::raw('SUM(r.sales_in_liters) as sales_in_liters'),
							DB::raw('SUM(r.ugt_pumping) as ugt_pumping'),
							DB::raw('SUM(r.delivery) as delivery'),
							DB::raw('SUM(r.ending_inventory) as ending_inventory'),
							DB::raw('SUM(r.book_stock) as book_stock'),
							DB::raw('SUM(r.variance) as variance')
						)
						->whereBetween('m.report_date', [$start_date, $end_date])
						->where('m.teves_branch', $company_header)
						->groupBy('m.report_date', 'r.tank_idx', 'm.teves_branch')
						->get();
						
			}
			
			
		$receivable_header = TevesBranchModel::find($company_header, ['branch_code','branch_name','branch_tin','branch_address','branch_contact_number','branch_owner','branch_owner_title','branch_logo']);

		/*USER INFO*/
		$user_data = User::where('user_id', '=', Session::get('loginID'))->first();
		
		$title = 'Cumulative Report';
		  
        $pdf = PDF::loadView('printables.report_cumulative_sales_report_pdf', compact('title', 'data', 'user_data','receivable_header','start_date','end_date'));
		
		/*Download Directly*/
        //return $pdf->download($client_data['client_name'].".pdf");
		/*Stream for Saving/Printing*/
		$pdf->setPaper('Legal', 'landscape');/*Set to Landscape*/
		return $pdf->stream($receivable_header['branch_code']."_Cumulative_Report.pdf");
		
		
	}		
	

}