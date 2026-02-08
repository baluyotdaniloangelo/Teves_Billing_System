<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ProductModel;
use App\Models\TevesBranchModel;

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

class NonCashReportController extends Controller
{
	
	/*Load Billing History Report Interface*/
	public function non_cash_report_page(){
		
		if(Session::has('loginID')){
			
			$title = 'Non-Cash Report';
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

			return view("pages.non-cash_report", compact('data','title','teves_branch'));
		
		}
	}  	
	
	public function generate_non_cash_payment(Request $request){

		$request->validate([
			'start_date' => 'required|date',
			'end_date'   => 'required|date',
		]);

		$current_user   = Session::get('loginID');
		$start_date     = $request->start_date;
		$end_date       = $request->end_date;
		$company_header = $request->company_header;
		$payment_type   = $request->payment_type;

		\DB::statement("SET SQL_MODE=''");

		$data = CashiersReportModel::query()

			/* ===============================
			 | BRANCH ACCESS
			 =============================== */
			->where(function ($q) use ($current_user) {
				if (Session::get('user_branch_access_type') === "BYBRANCH") {
					$q->whereRaw(
						"teves_cashiers_report.teves_branch 
						 IN (SELECT branch_idx 
							 FROM teves_user_branch_access 
							 WHERE user_idx = ?)",
						[$current_user]
					);
				}
			})

			/* ===============================
			 | DATE FILTER (IMPORTANT FIX)
			 =============================== */
			->whereDate('teves_cashiers_report.report_date', '>=', $start_date)
			->whereDate('teves_cashiers_report.report_date', '<=', $end_date)
			
			/* ===============================
			 | BRANCH FILTER (ALL OPTION)
			 =============================== */
			->when($company_header !== 'All', function ($q) use ($company_header) {
				$q->where('teves_cashiers_report.teves_branch', $company_header);
			})

			->whereNull('teves_cashiers_report.deleted_at')

			/* ===============================
			 | JOINS
			 =============================== */
			->leftJoin('user_tb', function ($join) {
				$join->on('user_tb.user_id', '=', 'teves_cashiers_report.user_idx')
					 ->whereNull('user_tb.deleted_at');
			})

			->leftJoin('teves_branch_table', function ($join) {
				$join->on('teves_branch_table.branch_id', '=', 'teves_cashiers_report.teves_branch')
					 ->whereNull('teves_branch_table.deleted_at');
			})

			->join( // 🔥 INNER JOIN ON P8 (non-cash only)
				'teves_cashiers_report_p8',
				'teves_cashiers_report_p8.cashiers_report_idx',
				'=',
				'teves_cashiers_report.cashiers_report_id'
			)

			/* ===============================
			 | PAYMENT TYPE FILTER
			 =============================== */
			->when(($payment_type !== 'All'), function ($q) use ($payment_type) {
				$q->where('teves_cashiers_report_p8.payment_type', $payment_type);
			})

			/* ===============================
			 | GROUPING
			 =============================== */
			->groupBy(
				'teves_cashiers_report.report_date',
				'teves_cashiers_report.shift',
				'teves_cashiers_report_p8.payment_type'
			)

			/* ===============================
			 | SELECT
			 =============================== */
			->selectRaw('
				teves_cashiers_report.report_date,
				teves_branch_table.branch_initial,
				teves_cashiers_report.shift,
				teves_cashiers_report.cashiers_name,
				teves_cashiers_report.forecourt_attendant,
				user_tb.user_real_name as encoder_name,
				teves_cashiers_report_p8.payment_type,
				SUM(teves_cashiers_report_p8.payment_amount) AS total_amount
			')

			->orderBy('teves_cashiers_report.report_date')
			->orderBy('teves_cashiers_report.shift')

			->get();

		return DataTables::of($data)
			->addIndexColumn()
			->make(true);

		
	}	
	
	public function generate_non_cash_payment_report_pdf(Request $request){

		$request->validate([
			'start_date' => 'required|date',
			'end_date'   => 'required|date',
		]);

		$current_user   = Session::get('loginID');
		$start_date     = $request->start_date;
		$end_date       = $request->end_date;
		$company_header = $request->company_header;
		$payment_type   = $request->payment_type;

		\DB::statement("SET SQL_MODE=''");

		$data = CashiersReportModel::query()

			/* ===============================
			 | BRANCH ACCESS
			 =============================== */
			->where(function ($q) use ($current_user) {
				if (Session::get('user_branch_access_type') === "BYBRANCH") {
					$q->whereRaw(
						"teves_cashiers_report.teves_branch 
						 IN (SELECT branch_idx 
							 FROM teves_user_branch_access 
							 WHERE user_idx = ?)",
						[$current_user]
					);
				}
			})

			/* ===============================
			 | DATE FILTER (IMPORTANT FIX)
			 =============================== */
			->whereDate('teves_cashiers_report.report_date', '>=', $start_date)
			->whereDate('teves_cashiers_report.report_date', '<=', $end_date)
			
			/* ===============================
			 | BRANCH FILTER (ALL OPTION)
			 =============================== */
			->when($company_header !== 'All', function ($q) use ($company_header) {
				$q->where('teves_cashiers_report.teves_branch', $company_header);
			})

			->whereNull('teves_cashiers_report.deleted_at')

			/* ===============================
			 | JOINS
			 =============================== */
			->leftJoin('user_tb', function ($join) {
				$join->on('user_tb.user_id', '=', 'teves_cashiers_report.user_idx')
					 ->whereNull('user_tb.deleted_at');
			})

			->leftJoin('teves_branch_table', function ($join) {
				$join->on('teves_branch_table.branch_id', '=', 'teves_cashiers_report.teves_branch')
					 ->whereNull('teves_branch_table.deleted_at');
			})

			->join( // 🔥 INNER JOIN ON P8 (non-cash only)
				'teves_cashiers_report_p8',
				'teves_cashiers_report_p8.cashiers_report_idx',
				'=',
				'teves_cashiers_report.cashiers_report_id'
			)

			/* ===============================
			 | PAYMENT TYPE FILTER
			 =============================== */
			->when(($payment_type !== 'All'), function ($q) use ($payment_type) {
				$q->where('teves_cashiers_report_p8.payment_type', $payment_type);
			})

			/* ===============================
			 | GROUPING
			 =============================== */
			->groupBy(
				'teves_cashiers_report.report_date',
				'teves_cashiers_report.shift',
				'teves_cashiers_report_p8.payment_type'
			)

			/* ===============================
			 | SELECT
			 =============================== */
			->selectRaw('
				teves_cashiers_report.report_date,
				teves_branch_table.branch_initial,
				teves_cashiers_report.shift,
				teves_cashiers_report.cashiers_name,
				teves_cashiers_report.forecourt_attendant,
				user_tb.user_real_name as encoder_name,
				teves_cashiers_report_p8.payment_type,
				SUM(teves_cashiers_report_p8.payment_amount) AS total_amount
			')

			->orderBy('teves_cashiers_report.report_date')
			->orderBy('teves_cashiers_report.shift')

			->get();
		
		if($company_header !== 'All'){		
				$receivable_header = TevesBranchModel::find($company_header, ['branch_code','branch_name','branch_tin','branch_address','branch_contact_number','branch_owner','branch_owner_title','branch_logo']);
		}else{
				$receivable_header = TevesBranchModel::find(1, ['branch_code','branch_name','branch_tin','branch_address','branch_contact_number','branch_owner','branch_owner_title','branch_logo']);
		}
		/*USER INFO*/
		$user_data = User::where('user_id', '=', Session::get('loginID'))->first();
		
		$title = 'Non Cash Payment';
		  
        $pdf = PDF::loadView('printables.report_non_cash_payment_report_pdf', compact('title', 'data', 'user_data','receivable_header','start_date','end_date'));
		
		/*Download Directly*/
        //return $pdf->download($client_data['client_name'].".pdf");
		/*Stream for Saving/Printing*/
		$pdf->setPaper('Legal', 'landscape');/*Set to Landscape*/
		return $pdf->stream($receivable_header['branch_code']."_NON_CASH_PAYMENT.pdf");
		
		
	}		
	

}