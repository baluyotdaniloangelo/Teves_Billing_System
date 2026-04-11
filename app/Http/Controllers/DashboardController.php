<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\BillingTransactionModel;
use App\Models\SOBillingTransactionModel;
use App\Models\ProductModel;
use App\Models\ClientModel;
use Session;
use Validator;
use DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\TevesBranchModel;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
	
	/*Load Site Interface*/
	public function dashboard(Request $request){
		
		if(Session::has('loginID')){
			
			$title = 'Dashboard';
			$data = array();
			
			$user_info = User::where('user_id', '=', Session::get('loginID'))->first();



/* ===============================
 | MAIN QUERY (FAST + FILTERED)
 =============================== */
    /* ===============================
     | VALIDATION (OPTIONAL FILTERS)
     =============================== */
    $request->validate([
        'start_date' => 'nullable|date',
        'end_date'   => 'nullable|date'
    ]);

    /* ===============================
     | DEFAULT = TODAY (OR RANGE)
     =============================== */
	$start_date = now()->subDays(6)->toDateString();
    //$start_date     = $request->start_date ?? now()->toDateString();
    $end_date       = $request->end_date ?? now()->toDateString();
    $company_header = $request->company_header ?? 'All';
    $current_user   = auth()->user()->user_id ?? null;


    /* ===============================
     | MAIN QUERY (DAILY TOTAL ONLY)
     =============================== */
	$result = DB::table('teves_cashiers_report as r')

		->leftJoin('teves_branch_table as b', 'b.branch_id', '=', 'r.teves_branch')

		->when(Session::get('user_branch_access_type') === "BYBRANCH", function ($q) use ($current_user) {
			$q->whereRaw("
				r.teves_branch IN (
					SELECT branch_idx 
					FROM teves_user_branch_access 
					WHERE user_idx = ?
				)
			", [$current_user]);
		})

		->whereDate('r.report_date', '>=', $start_date)
		->whereDate('r.report_date', '<=', $end_date)

		->when($company_header !== 'All', function ($q) use ($company_header) {
			$q->where('r.teves_branch', $company_header);
		})

		->whereNull('r.deleted_at')

		->selectRaw('
			r.report_date,

			COALESCE(b.branch_code, r.teves_branch) as branch_code,
			b.branch_name,

			SUM(r.total_sales) as total_sales,
			SUM(r.total_cash_sales) as total_cash_sales,
			SUM(r.non_cash_payment) as non_cash_payment,
			SUM(r.short_over) as short_over
		')

		->groupBy('r.report_date', 'b.branch_code', 'b.branch_name', 'r.teves_branch')
		->orderBy('r.report_date')
		->get();


    /* ===============================
     | RETURN VIEW
     =============================== */
			

			return view('dashboard.dashboard', [
				'data' => $user_info,
				'result' => $result,
				'start_date' => $start_date,
				'end_date' => $end_date, 'title' => $title
			]);
		
		}
	}   
 	
}
