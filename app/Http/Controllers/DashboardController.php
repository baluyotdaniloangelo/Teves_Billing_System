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
    $result = DB::table('teves_cashiers_report')

        ->when(Session::get('user_branch_access_type') === "BYBRANCH", function ($q) use ($current_user) {
            $q->whereRaw("
                teves_branch IN (
                    SELECT branch_idx 
                    FROM teves_user_branch_access 
                    WHERE user_idx = ?
                )
            ", [$current_user]);
        })

        ->whereDate('report_date', '>=', $start_date)
        ->whereDate('report_date', '<=', $end_date)

        ->when($company_header !== 'All', function ($q) use ($company_header) {
            $q->where('teves_branch', $company_header);
        })

        ->whereNull('deleted_at')

        ->selectRaw('
            report_date,

            SUM(total_sales) as total_sales,
            SUM(other_sales) as other_sales,
            SUM(cash_transaction) as cash_transaction,
            SUM(fuel_sales) as fuel_sales,
            SUM(discount) as discount,
            SUM(cashout_other) as cashout_other,
            SUM(theoretical_sales) as theoretical_sales,
            SUM(non_cash_payment) as non_cash_payment,
            SUM(total_cash_sales) as total_cash_sales,
            SUM(short_over) as short_over
        ')

        ->groupBy('report_date')
        ->orderBy('report_date')
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
