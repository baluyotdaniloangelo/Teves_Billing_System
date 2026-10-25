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
	private function applyDashboardBranchAccess($query, $column, $userId)
	{
		return $query->when(Session::get('user_branch_access_type') === 'BYBRANCH', function ($q) use ($column, $userId) {
			$q->whereIn($column, function ($access) use ($userId) {
				$access->select('branch_idx')->from('teves_user_branch_access')->where('user_idx', $userId);
			});
		});
	}

	public function salesReceivables(Request $request)
	{
		if (!Session::has('loginID')) return redirect('/login');
		$user = User::where('user_id', Session::get('loginID'))->first();
		if (!$user || $user->user_type !== 'SUAdmin') return redirect('/billing');

		$request->validate([
			'start_date' => 'nullable|date_format:Y-m-d',
			'end_date' => 'nullable|date_format:Y-m-d',
			'company_header' => 'nullable|string|max:32',
			'client_id' => 'nullable|integer|exists:teves_client_table,client_id',
		]);
		$endDate = $request->input('end_date') ?: now()->toDateString();
		$startDate = $request->input('start_date') ?: \Carbon\Carbon::parse($endDate)->subDays(29)->toDateString();
		if ($startDate > $endDate) {
			throw \Illuminate\Validation\ValidationException::withMessages(['end_date' => 'The end date must be on or after the start date.']);
		}

		$userId = Session::get('loginID');
		$branches = TevesBranchModel::query()
			->when(Session::get('user_branch_access_type') === 'BYBRANCH', function ($q) use ($userId) {
				$q->whereIn('branch_id', function ($access) use ($userId) {
					$access->select('branch_idx')->from('teves_user_branch_access')->where('user_idx', $userId);
				});
			})
			->orderBy('branch_code')->get(['branch_id', 'branch_code', 'branch_name']);
		$clients = ClientModel::query()->whereNull('deleted_at')->orderBy('client_name')->get(['client_id', 'client_name']);
		$branchId = $request->input('company_header') ?: 'All';
		if ($branchId !== 'All' && !$branches->contains(fn ($branch) => (string) $branch->branch_id === (string) $branchId)) {
			throw \Illuminate\Validation\ValidationException::withMessages(['company_header' => 'Choose an accessible branch.']);
		}
		$clientId = $request->input('client_id') ?: 'All';

		$orderQuery = function () use ($userId, $branchId, $clientId) {
			$query = DB::table('teves_sales_order_table as so')
				->leftJoin('teves_client_table as c', 'c.client_id', '=', 'so.sales_order_client_idx')
				->leftJoin('teves_branch_table as b', 'b.branch_id', '=', 'so.company_header')
				->whereNull('so.deleted_at');
			$this->applyDashboardBranchAccess($query, 'so.company_header', $userId);
			return $query
				->when($branchId !== 'All', fn ($q) => $q->where('so.company_header', $branchId))
				->when($clientId !== 'All', fn ($q) => $q->where('so.sales_order_client_idx', $clientId));
		};
		$periodOrders = $orderQuery()->whereBetween('so.sales_order_date', [$startDate, $endDate]);
		$authorizedOrders = (clone $periodOrders)->where('so.sales_order_quotation', 0);
		$orderSales = (float) (clone $authorizedOrders)->sum('so.sales_order_total_due');
		$orderCount = (int) (clone $authorizedOrders)->count('so.sales_order_id');
		$pipeline = (clone $authorizedOrders)
			->selectRaw("CASE WHEN so.sales_order_delivery_status = 'Pending' THEN 'Pending' WHEN so.sales_order_delivery_status LIKE '100%' THEN 'Delivered' ELSE 'Partially delivered' END as status, COUNT(*) as order_count, SUM(so.sales_order_total_due) as amount")
			->groupBy('status')->orderBy('status')->get();
		$approvalQuery = (clone $periodOrders)->where('so.sales_order_quotation', 1);
		$approvalCount = (int) (clone $approvalQuery)->count('so.sales_order_id');
		$approvalAmount = (float) (clone $approvalQuery)->sum('so.sales_order_total_due');

		$directBillingQuery = function () use ($userId, $branchId, $clientId, $startDate, $endDate) {
			$query = DB::table('teves_billing_table as bill')->whereNull('bill.deleted_at');
			$this->applyDashboardBranchAccess($query, 'bill.branch_idx', $userId);
			return $query->whereBetween('bill.order_date', [$startDate, $endDate])
				->where(function ($q) { $q->whereNull('bill.sales_order_idx')->orWhere('bill.sales_order_idx', 0); })
				->when($branchId !== 'All', fn ($q) => $q->where('bill.branch_idx', $branchId))
				->when($clientId !== 'All', fn ($q) => $q->where('bill.client_idx', $clientId));
		};
		$directBillingAmount = (float) (clone $directBillingQuery())->sum('bill.order_total_amount');
		$directBillingCount = (int) (clone $directBillingQuery())->distinct()->count('bill.billing_id');
		$unbilledAmount = (float) (clone $directBillingQuery())->where('bill.receivable_idx', 0)->sum('bill.order_total_amount');
		$unbilledCount = (int) (clone $directBillingQuery())->where('bill.receivable_idx', 0)->distinct()->count('bill.billing_id');

		$receivableQuery = function () use ($userId, $branchId, $clientId) {
			$query = DB::table('teves_receivable_table as ar')
				->leftJoin('teves_client_table as c', 'c.client_id', '=', 'ar.client_idx')
				->whereNull('ar.deleted_at')->where('ar.receivable_remaining_balance', '>', 0);
			$this->applyDashboardBranchAccess($query, 'ar.company_header', $userId);
			return $query
				->when($branchId !== 'All', fn ($q) => $q->where('ar.company_header', $branchId))
				->when($clientId !== 'All', fn ($q) => $q->where('ar.client_idx', $clientId));
		};
		$openReceivables = $receivableQuery()->get([
			'ar.receivable_id', 'ar.sales_order_idx', 'ar.client_idx', 'ar.control_number', 'ar.billing_date',
			'ar.receivable_amount', 'ar.receivable_remaining_balance', 'ar.receivable_status', 'ar.created_at', 'c.client_name',
		]);
		$openArAmount = (float) $openReceivables->sum('receivable_remaining_balance');
		$openArCount = $openReceivables->count();
		$aging = ['0–30 days' => 0.0, '31–60 days' => 0.0, '61–90 days' => 0.0, '90+ days' => 0.0];
		$clientBalances = [];
		foreach ($openReceivables as $receivable) {
			$age = $receivable->billing_date ? \Carbon\Carbon::parse($receivable->billing_date)->startOfDay()->diffInDays(now()->startOfDay()) : 0;
			$bucket = $age <= 30 ? '0–30 days' : ($age <= 60 ? '31–60 days' : ($age <= 90 ? '61–90 days' : '90+ days'));
			$aging[$bucket] += (float) $receivable->receivable_remaining_balance;
			$name = $receivable->client_name ?: 'Unassigned client';
			$clientBalances[$name] = ($clientBalances[$name] ?? 0) + (float) $receivable->receivable_remaining_balance;
		}
		arsort($clientBalances);
		$topReceivableClients = collect($clientBalances)->take(5);

		$paymentQuery = DB::table('teves_receivable_payment as p')
			->join('teves_receivable_table as ar', 'ar.receivable_id', '=', 'p.receivable_idx')
			->leftJoin('teves_client_table as c', 'c.client_id', '=', 'ar.client_idx')
			->whereNull('ar.deleted_at')->whereNull('p.deleted_at')
			->whereBetween('p.receivable_date_of_payment', [$startDate, $endDate]);
		$this->applyDashboardBranchAccess($paymentQuery, 'ar.company_header', $userId);
		$paymentQuery->when($branchId !== 'All', fn ($q) => $q->where('ar.company_header', $branchId))
			->when($clientId !== 'All', fn ($q) => $q->where('ar.client_idx', $clientId));
		$collections = (float) (clone $paymentQuery)->sum('p.receivable_payment_amount');
		$collectionCount = (int) (clone $paymentQuery)->count('p.receivable_payment_id');

		$pendingDeliveries = $orderQuery()->where('so.sales_order_quotation', 0)->where('so.sales_order_delivery_status', 'Pending')
			->whereNotNull('so.sales_order_required_date')->whereDate('so.sales_order_required_date', '<', now()->toDateString())
			->orderBy('so.sales_order_required_date')->limit(6)
			->get(['so.sales_order_id', 'so.sales_order_control_number', 'so.sales_order_required_date', 'so.sales_order_total_due', 'c.client_name']);
		$oldReceivables = $openReceivables->filter(function ($receivable) {
			return $receivable->billing_date && \Carbon\Carbon::parse($receivable->billing_date)->startOfDay()->diffInDays(now()->startOfDay()) > 60;
		})->sortByDesc(fn ($receivable) => (float) $receivable->receivable_remaining_balance)->take(6)->values();
		$unbilledRows = (clone $directBillingQuery())->where('bill.receivable_idx', 0)
			->leftJoin('teves_client_table as bill_client', 'bill_client.client_id', '=', 'bill.client_idx')
			->orderByDesc('bill.order_date')->limit(6)
			->get(['bill.billing_id', 'bill.order_date', 'bill.order_total_amount', 'bill_client.client_name']);

		$trendOrders = (clone $authorizedOrders)->selectRaw('DATE(so.sales_order_date) as day, SUM(so.sales_order_total_due) as amount')
			->groupBy(DB::raw('DATE(so.sales_order_date)'))->orderBy('day')->get();
		$trendBilling = (clone $directBillingQuery())->selectRaw('DATE(bill.order_date) as day, SUM(bill.order_total_amount) as amount')
			->groupBy(DB::raw('DATE(bill.order_date)'))->orderBy('day')->get();
		$trendCollections = (clone $paymentQuery)->selectRaw('DATE(p.receivable_date_of_payment) as day, SUM(p.receivable_payment_amount) as amount')
			->groupBy(DB::raw('DATE(p.receivable_date_of_payment)'))->orderBy('day')->get();
		$trendByDay = [];
		foreach ($trendOrders as $row) $trendByDay[$row->day]['orders'] = (float) $row->amount;
		foreach ($trendBilling as $row) $trendByDay[$row->day]['billing'] = (float) $row->amount;
		foreach ($trendCollections as $row) $trendByDay[$row->day]['collections'] = (float) $row->amount;
		$trend = [];
		foreach (\Carbon\CarbonPeriod::create($startDate, $endDate) as $day) {
			$key = $day->toDateString();
			$trend[] = ['date' => $key, 'orders' => $trendByDay[$key]['orders'] ?? 0, 'billing' => $trendByDay[$key]['billing'] ?? 0, 'collections' => $trendByDay[$key]['collections'] ?? 0];
		}

		$recentOrders = (clone $authorizedOrders)->leftJoin('teves_client_table as recent_client', 'recent_client.client_id', '=', 'so.sales_order_client_idx')
			->orderByDesc('so.created_at')->limit(5)->get(['so.sales_order_id as id', 'so.sales_order_control_number as reference', 'so.created_at as occurred_at', 'so.sales_order_total_due as amount', 'recent_client.client_name', DB::raw("'Sales Order' as activity_type")]);
		$recentBillings = (clone $directBillingQuery())->leftJoin('teves_client_table as recent_bill_client', 'recent_bill_client.client_id', '=', 'bill.client_idx')
			->orderByDesc('bill.created_at')->limit(5)->get(['bill.billing_id as id', 'bill.order_po_number as reference', 'bill.created_at as occurred_at', 'bill.order_total_amount as amount', 'recent_bill_client.client_name', DB::raw("'Billing' as activity_type")]);
		$recentPayments = (clone $paymentQuery)->orderByDesc('p.created_at')->limit(5)
			->get(['p.receivable_payment_id as id', 'p.receivable_reference as reference', 'p.created_at as occurred_at', 'p.receivable_payment_amount as amount', 'c.client_name', DB::raw("'Payment' as activity_type")]);
		$recentActivity = $recentOrders->concat($recentBillings)->concat($recentPayments)->sortByDesc('occurred_at')->take(8)->values();

		return view('dashboard.sales_receivables', [
			'data' => $user, 'title' => 'Sales & Receivables Dashboard', 'branches' => $branches, 'clients' => $clients,
			'branch_id' => $branchId, 'client_id' => $clientId, 'start_date' => $startDate, 'end_date' => $endDate,
			'order_sales' => $orderSales, 'order_count' => $orderCount, 'pipeline' => $pipeline,
			'approval_count' => $approvalCount, 'approval_amount' => $approvalAmount,
			'direct_billing_amount' => $directBillingAmount, 'direct_billing_count' => $directBillingCount,
			'unbilled_amount' => $unbilledAmount, 'unbilled_count' => $unbilledCount,
			'open_ar_amount' => $openArAmount, 'open_ar_count' => $openArCount, 'aging' => $aging,
			'top_receivable_clients' => $topReceivableClients, 'collections' => $collections, 'collection_count' => $collectionCount,
			'pending_deliveries' => $pendingDeliveries, 'old_receivables' => $oldReceivables, 'unbilled_rows' => $unbilledRows,
			'trend' => $trend, 'recent_activity' => $recentActivity,
		]);
	}
	
	/*Load Site Interface*/
	public function dashboard(Request $request){
		
		if(Session::has('loginID')){
			
			$title = 'Cashier Dashboard';
			$data = array();
			
			$user_info = User::where('user_id', '=', Session::get('loginID'))->first();
		
		// 🔥 REDIRECT NON-SUAdmin
		if ($user_info->user_type !== 'SUAdmin') {
			return redirect('/billing');
		}
	
		/* ===============================
		 | MAIN QUERY (FAST + FILTERED)
		 =============================== */
		/* ===============================
		 | VALIDATION (OPTIONAL FILTERS)
		 =============================== */
		$request->validate([
			'start_date' => 'nullable|date_format:Y-m-d',
			'end_date'   => 'nullable|date_format:Y-m-d'
		]);

		/* ===============================
		 | DEFAULT = TODAY (OR RANGE)
		 =============================== */
		$end_date = $request->input('end_date') ?: now()->toDateString();
		$start_date = $request->input('start_date') ?: \Carbon\Carbon::parse($end_date)->subDays(6)->toDateString();
		if ($start_date > $end_date) {
			throw \Illuminate\Validation\ValidationException::withMessages([
				'end_date' => 'The end date must be on or after the start date.'
			]);
		}

		// The selector uses the same branch access restriction as the report query.
		$branches = TevesBranchModel::query()
			->when(Session::get('user_branch_access_type') === 'BYBRANCH', function ($q) {
				$q->whereIn('branch_id', function ($access) {
					$access->select('branch_idx')->from('teves_user_branch_access')
						->where('user_idx', Session::get('loginID'));
				});
			})
			->orderBy('branch_code')
			->get(['branch_id', 'branch_code', 'branch_name']);
		$request->validate([
			'company_header' => ['nullable', Rule::in(array_merge(['All'], $branches->pluck('branch_id')->all()))]
		]);
		$company_header = $request->input('company_header') ?: 'All';
		//$current_user   = auth()->user()->user_id ?? null;
		$current_user = Session::get('loginID');
		$previousPeriodEnd = \Carbon\Carbon::parse($start_date)->subDay();
		$periodDays = \Carbon\Carbon::parse($start_date)->diffInDays(\Carbon\Carbon::parse($end_date)) + 1;
		$previousPeriodStart = $previousPeriodEnd->copy()->subDays($periodDays - 1);

		// Reuse the same branch visibility rules for every dashboard data set.
		$reportQuery = function () use ($current_user, $company_header) {
			return DB::table('teves_cashiers_report as r')
				->when(Session::get('user_branch_access_type') === 'BYBRANCH', function ($q) use ($current_user) {
					$q->whereIn('r.teves_branch', function ($access) use ($current_user) {
						$access->select('branch_idx')->from('teves_user_branch_access')->where('user_idx', $current_user);
					});
				})
				->when($company_header !== 'All', function ($q) use ($company_header) {
					$q->where('r.teves_branch', $company_header);
				})
				->where('r.cashier_report_type', 'FUEL')
				->whereNull('r.deleted_at');
		};


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
			->where('r.cashier_report_type', 'FUEL')

			->whereNull('r.deleted_at')

			->selectRaw('
				r.report_date,
				r.teves_branch as branch_id,

				COALESCE(b.branch_code, r.teves_branch) as branch_code,
				b.branch_name,

				SUM(r.total_sales) as total_sales,
				SUM(r.total_cash_sales) as total_cash_sales,
				SUM(r.non_cash_payment) as non_cash_payment,
				SUM(r.short_over) as short_over
			')

			->groupBy('r.report_date', 'b.branch_code', 'b.branch_name', 'r.teves_branch')
			->orderBy('r.report_date', 'desc')
			//->orderByDesc('total_sales')
			->get();

		$previousSales = $reportQuery()
			->whereDate('r.report_date', '>=', $previousPeriodStart->toDateString())
			->whereDate('r.report_date', '<=', $previousPeriodEnd->toDateString())
			->sum('r.total_sales');

		$shiftBreakdown = $reportQuery()
			->whereDate('r.report_date', '>=', $start_date)
			->whereDate('r.report_date', '<=', $end_date)
			->selectRaw('r.shift, SUM(r.total_sales) as total_sales, SUM(r.short_over) as short_over, COUNT(*) as report_count')
			->groupBy('r.shift')->orderBy('r.shift')->get();

		$productBreakdown = $reportQuery()
			->join('teves_cashiers_report_p1 as p1', 'p1.cashiers_report_id', '=', 'r.cashiers_report_id')
			->leftJoin('teves_product_table as p', 'p.product_id', '=', 'p1.product_idx')
			->whereDate('r.report_date', '>=', $start_date)
			->whereDate('r.report_date', '<=', $end_date)
			->selectRaw('p1.product_idx, COALESCE(p.product_name, CONCAT("Product ", p1.product_idx)) as product_name, SUM(p1.order_total_amount) as total_sales, SUM(p1.order_quantity) as quantity')
			->groupBy('p1.product_idx', 'p.product_name')
			->orderByDesc('total_sales')->limit(8)->get();

		$recentActivity = $reportQuery()
			->leftJoin('teves_branch_table as b', 'b.branch_id', '=', 'r.teves_branch')
			->whereDate('r.report_date', '>=', $start_date)
			->whereDate('r.report_date', '<=', $end_date)
			->select('r.cashiers_report_id', 'r.cashiers_report_no', 'r.report_date', 'r.shift', 'r.cashiers_name', 'r.total_sales', 'r.short_over', 'r.created_at', 'b.branch_code')
			->orderByDesc('r.created_at')->limit(8)->get();

		$shortageAlerts = $reportQuery()
			->whereDate('r.report_date', '>=', $start_date)
			->whereDate('r.report_date', '<=', $end_date)
			->where('r.short_over', '<', 0)
			->selectRaw('COUNT(*) as count, SUM(r.short_over) as total_short, MIN(r.short_over) as largest_short')
			->first();


		/* ===============================
		 | RETURN VIEW
		 =============================== */
			

			return view('dashboard.fuel', [
				'data' => $user_info,
				'result' => $result,
				'branches' => $branches,
				'company_header' => $company_header,
				'start_date' => $start_date,
				'end_date' => $end_date, 'title' => $title,
				'previous_sales' => (float) $previousSales,
				'previous_period_start' => $previousPeriodStart->toDateString(),
				'previous_period_end' => $previousPeriodEnd->toDateString(),
				'shift_breakdown' => $shiftBreakdown,
				'product_breakdown' => $productBreakdown,
				'recent_activity' => $recentActivity,
				'shortage_alerts' => $shortageAlerts,
			]);
		
		}
	}   
 	
}
