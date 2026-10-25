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

	public function fuelSalesVolume(Request $request)
	{
		if (!Session::has('loginID')) return redirect('/login');
		$userId = Session::get('loginID');
		$user = User::where('user_id', $userId)->first();
		if (!$user || $user->user_type !== 'SUAdmin') return redirect('/billing');

		$request->validate([
			'start_date' => 'nullable|date_format:Y-m-d',
			'end_date' => 'nullable|date_format:Y-m-d',
			'company_header' => 'nullable|string|max:32',
			'product_id' => 'nullable|integer|exists:teves_product_table,product_id',
		]);
		$endDate = $request->input('end_date') ?: now()->toDateString();
		$startDate = $request->input('start_date') ?: \Carbon\Carbon::parse($endDate)->subDays(29)->toDateString();
		if ($startDate > $endDate) {
			throw \Illuminate\Validation\ValidationException::withMessages(['end_date' => 'The end date must be on or after the start date.']);
		}

		$branches = TevesBranchModel::query()
			->when(Session::get('user_branch_access_type') === 'BYBRANCH', function ($query) use ($userId) {
				$query->whereIn('branch_id', function ($access) use ($userId) {
					$access->select('branch_idx')->from('teves_user_branch_access')->where('user_idx', $userId);
				});
			})
			->orderBy('branch_code')->get(['branch_id', 'branch_code', 'branch_name']);
		$branchId = $request->input('company_header') ?: 'All';
		if ($branchId !== 'All' && !$branches->contains(fn ($branch) => (string) $branch->branch_id === (string) $branchId)) {
			throw \Illuminate\Validation\ValidationException::withMessages(['company_header' => 'Choose an accessible branch.']);
		}

		$fuelProducts = DB::table('teves_product_table as product')
			->join('teves_product_category as category', 'category.category_id', '=', 'product.category_idx')
			->whereNull('product.deleted_at')->whereNull('category.deleted_at')
			->whereRaw('LOWER(TRIM(category.category_name)) LIKE ?', ['%fuel%'])
			->orderBy('product.product_name')
			->get(['product.product_id', 'product.product_name']);
		$productId = $request->input('product_id') ?: 'All';
		if ($productId !== 'All' && !$fuelProducts->contains(fn ($product) => (string) $product->product_id === (string) $productId)) {
			throw \Illuminate\Validation\ValidationException::withMessages(['product_id' => 'Choose a Fuel product.']);
		}

		$deliveryQuery = function () use ($userId, $branchId, $productId, $startDate, $endDate) {
			$query = DB::table('teves_sales_order_delivery_details as delivery')
				->join('teves_sales_order_table as so', 'so.sales_order_id', '=', 'delivery.sales_order_idx')
				->join('teves_product_table as product', 'product.product_id', '=', 'delivery.sales_order_component_product_idx')
				->join('teves_product_category as category', 'category.category_id', '=', 'product.category_idx')
				->leftJoin('teves_client_table as client', 'client.client_id', '=', 'so.sales_order_client_idx')
				->leftJoin('teves_branch_table as branch', 'branch.branch_id', '=', 'so.company_header')
				->whereNull('so.deleted_at')->whereNull('product.deleted_at')->whereNull('category.deleted_at')
				->where('so.sales_order_quotation', 0)
				->whereBetween('delivery.sales_order_delivery_date', [$startDate, $endDate])
				->whereRaw('LOWER(TRIM(category.category_name)) LIKE ?', ['%fuel%']);
			$this->applyDashboardBranchAccess($query, 'so.company_header', $userId);
			return $query
				->when($branchId !== 'All', fn ($q) => $q->where('so.company_header', $branchId))
				->when($productId !== 'All', fn ($q) => $q->where('product.product_id', $productId));
		};

		$retailQuery = function () use ($userId, $branchId, $productId, $startDate, $endDate) {
			$query = DB::table('teves_cashiers_report_p1 as sale')
				->join('teves_cashiers_report as report', 'report.cashiers_report_id', '=', 'sale.cashiers_report_id')
				->join('teves_product_table as product', 'product.product_id', '=', 'sale.product_idx')
				->join('teves_product_category as category', 'category.category_id', '=', 'product.category_idx')
				->leftJoin('teves_branch_table as branch', 'branch.branch_id', '=', 'report.teves_branch')
				->where('report.cashier_report_type', 'FUEL')->whereNull('report.deleted_at')
				->whereNull('product.deleted_at')->whereNull('category.deleted_at')
				->whereBetween('report.report_date', [$startDate, $endDate])
				->whereRaw('LOWER(TRIM(category.category_name)) LIKE ?', ['%fuel%']);
			$this->applyDashboardBranchAccess($query, 'report.teves_branch', $userId);
			return $query
				->when($branchId !== 'All', fn ($q) => $q->where('report.teves_branch', $branchId))
				->when($productId !== 'All', fn ($q) => $q->where('product.product_id', $productId));
		};

		$orderLiters = (float) $deliveryQuery()->sum('delivery.sales_order_delivery_quantity');
		$orderCount = (int) $deliveryQuery()->distinct()->count('so.sales_order_id');
		$activeCustomers = (int) $deliveryQuery()->whereNotNull('so.sales_order_client_idx')->distinct()->count('so.sales_order_client_idx');
		$retailLiters = (float) $retailQuery()->sum('sale.order_quantity');
		$retailEntryCount = (int) $retailQuery()->count('sale.cashiers_report_p1_id');

		$customerSales = $deliveryQuery()
			->selectRaw('so.sales_order_client_idx as client_id, COALESCE(client.client_name, "Unassigned customer") as client_name, COUNT(DISTINCT so.sales_order_id) as order_count, SUM(delivery.sales_order_delivery_quantity) as delivered_liters, MAX(delivery.sales_order_delivery_date) as last_delivery')
			->groupBy('so.sales_order_client_idx', 'client.client_name')
			->orderByDesc('delivered_liters')->limit(25)->get();
		$customerProductRows = $deliveryQuery()
			->selectRaw('so.sales_order_client_idx as client_id, product.product_name, SUM(delivery.sales_order_delivery_quantity) as delivered_liters')
			->groupBy('so.sales_order_client_idx', 'product.product_name')->orderByDesc('delivered_liters')->get();
		$topProductByClient = [];
		foreach ($customerProductRows as $row) {
			$key = $row->client_id === null ? 'unassigned' : (string) $row->client_id;
			if (!array_key_exists($key, $topProductByClient)) $topProductByClient[$key] = $row->product_name;
		}
		foreach ($customerSales as $customer) {
			$key = $customer->client_id === null ? 'unassigned' : (string) $customer->client_id;
			$customer->top_product = $topProductByClient[$key] ?? '—';
		}

		$retailByBranch = $retailQuery()
			->selectRaw('report.teves_branch as branch_id, COALESCE(branch.branch_code, "Branch") as branch_code, COALESCE(branch.branch_name, "") as branch_name, COUNT(DISTINCT report.cashiers_report_id) as report_count, SUM(sale.order_quantity) as sold_liters')
			->groupBy('report.teves_branch', 'branch.branch_code', 'branch.branch_name')->orderByDesc('sold_liters')->get();
		$retailByProduct = $retailQuery()
			->selectRaw('product.product_id, product.product_name, SUM(sale.order_quantity) as sold_liters')
			->groupBy('product.product_id', 'product.product_name')->orderByDesc('sold_liters')->get();
		$orderByProduct = $deliveryQuery()
			->selectRaw('product.product_id, product.product_name, SUM(delivery.sales_order_delivery_quantity) as sold_liters')
			->groupBy('product.product_id', 'product.product_name')->orderByDesc('sold_liters')->get();
		$productMix = [];
		foreach ($orderByProduct as $row) {
			$productMix[$row->product_id] = ['product_name' => $row->product_name, 'sales_order_liters' => (float) $row->sold_liters, 'retail_liters' => 0.0];
		}
		foreach ($retailByProduct as $row) {
			if (!isset($productMix[$row->product_id])) $productMix[$row->product_id] = ['product_name' => $row->product_name, 'sales_order_liters' => 0.0, 'retail_liters' => 0.0];
			$productMix[$row->product_id]['retail_liters'] = (float) $row->sold_liters;
		}
		$productMix = collect(array_values($productMix))
			->map(function ($row) { $row['total_liters'] = $row['sales_order_liters'] + $row['retail_liters']; return $row; })
			->sortByDesc('total_liters')->values();

		$orderTrend = $deliveryQuery()
			->selectRaw('delivery.sales_order_delivery_date as day, SUM(delivery.sales_order_delivery_quantity) as liters')
			->groupBy('delivery.sales_order_delivery_date')->orderBy('day')->get();
		$retailTrend = $retailQuery()
			->selectRaw('report.report_date as day, SUM(sale.order_quantity) as liters')
			->groupBy('report.report_date')->orderBy('day')->get();
		$trendByDay = [];
		foreach ($orderTrend as $row) $trendByDay[$row->day]['sales_order'] = (float) $row->liters;
		foreach ($retailTrend as $row) $trendByDay[$row->day]['retail'] = (float) $row->liters;
		$trend = [];
		foreach (\Carbon\CarbonPeriod::create($startDate, $endDate) as $day) {
			$key = $day->toDateString();
			$trend[] = ['date' => $key, 'sales_order' => $trendByDay[$key]['sales_order'] ?? 0, 'retail' => $trendByDay[$key]['retail'] ?? 0];
		}

		return view('dashboard.fuel_sales_volume', [
			'data' => $user, 'title' => 'Fuel Sales Volume Dashboard', 'branches' => $branches,
			'fuel_products' => $fuelProducts, 'branch_id' => $branchId, 'product_id' => $productId,
			'start_date' => $startDate, 'end_date' => $endDate,
			'order_liters' => $orderLiters, 'order_count' => $orderCount, 'active_customers' => $activeCustomers,
			'retail_liters' => $retailLiters, 'retail_entry_count' => $retailEntryCount,
			'customer_sales' => $customerSales, 'product_mix' => $productMix,
			'retail_by_branch' => $retailByBranch, 'trend' => $trend,
		]);
	}
	
	public function procurementExpenses(Request $request)
	{
		if (!Session::has('loginID')) return redirect('/login');
		$user = User::where('user_id', Session::get('loginID'))->first();
		if (!$user || $user->user_type !== 'SUAdmin') return redirect('/billing');

		$request->validate([
			'start_date' => 'nullable|date_format:Y-m-d',
			'end_date' => 'nullable|date_format:Y-m-d',
			'company_header' => 'nullable|string|max:32',
			'source' => 'nullable|in:All,Fuel,LPG',
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
		$branchId = $request->input('company_header') ?: 'All';
		if ($branchId !== 'All' && !$branches->contains(fn ($branch) => (string) $branch->branch_id === (string) $branchId)) {
			throw \Illuminate\Validation\ValidationException::withMessages(['company_header' => 'Choose an accessible branch.']);
		}
		$source = $request->input('source') ?: 'All';
		$showFuel = $source === 'All' || $source === 'Fuel';
		$showLpg = $source === 'All' || $source === 'LPG';

		$purchaseOrderQuery = function () use ($userId, $branchId, $startDate, $endDate) {
			$query = DB::table('teves_purchase_order_table as po')
				->leftJoin('teves_supplier_table as supplier', 'supplier.supplier_id', '=', 'po.purchase_order_supplier_idx')
				->leftJoin('teves_branch_table as branch', 'branch.branch_id', '=', 'po.company_header');
			$this->applyDashboardBranchAccess($query, 'po.company_header', $userId);
			return $query->whereBetween('po.purchase_order_date', [$startDate, $endDate])
				->when($branchId !== 'All', fn ($q) => $q->where('po.company_header', $branchId));
		};
		$purchaseOrderCount = (int) $purchaseOrderQuery()->count('po.purchase_order_id');
		$purchaseOrderAmount = (float) $purchaseOrderQuery()->sum('po.purchase_order_total_payable');

		$purchaseOrderMetrics = function () use ($purchaseOrderQuery) {
			return $purchaseOrderQuery()
				->leftJoin(DB::raw('(SELECT purchase_order_idx, SUM(order_quantity) as ordered_quantity FROM teves_purchase_order_component_table GROUP BY purchase_order_idx) as po_components'), 'po_components.purchase_order_idx', '=', 'po.purchase_order_id')
				->leftJoin(DB::raw('(SELECT purchase_order_idx, SUM(purchase_order_delivery_quantity) as delivered_quantity FROM teves_purchase_order_delivery_details GROUP BY purchase_order_idx) as po_deliveries'), 'po_deliveries.purchase_order_idx', '=', 'po.purchase_order_id');
		};
		$deliveryBucketSql = "CASE WHEN COALESCE(po_components.ordered_quantity, 0) <= 0 THEN 'Not tracked' WHEN COALESCE(po_deliveries.delivered_quantity, 0) <= 0 THEN 'Pending' WHEN COALESCE(po_deliveries.delivered_quantity, 0) >= COALESCE(po_components.ordered_quantity, 0) THEN 'Received' ELSE 'Partial' END";
		$purchaseOrderPipeline = $purchaseOrderMetrics()
			->selectRaw($deliveryBucketSql . ' as delivery_bucket, COUNT(*) as order_count, SUM(COALESCE(po.purchase_order_total_payable, 0)) as amount')
			->groupBy(DB::raw($deliveryBucketSql))->get()->keyBy('delivery_bucket');
		$openToReceive = $purchaseOrderMetrics()->where('po_components.ordered_quantity', '>', 0)
			->whereRaw('COALESCE(po_deliveries.delivered_quantity, 0) < po_components.ordered_quantity');
		$openToReceiveCount = (int) (clone $openToReceive)->count('po.purchase_order_id');
		$openToReceiveAmount = (float) (clone $openToReceive)->sum('po.purchase_order_total_payable');
		$recentPurchaseOrders = $purchaseOrderMetrics()
			->orderByDesc('po.purchase_order_date')->orderByDesc('po.purchase_order_id')->limit(8)
			->get([
				'po.purchase_order_id', 'po.purchase_order_control_number', 'po.purchase_order_date',
				'po.purchase_order_total_payable', 'po.purchase_status', 'supplier.supplier_name',
				'branch.branch_code', 'branch.branch_name', 'po_components.ordered_quantity', 'po_deliveries.delivered_quantity',
			]);
		foreach ($recentPurchaseOrders as $order) {
			$ordered = (float) ($order->ordered_quantity ?? 0);
			$delivered = (float) ($order->delivered_quantity ?? 0);
			$order->delivery_bucket = $ordered <= 0 ? 'Not tracked' : ($delivered <= 0 ? 'Pending' : ($delivered >= $ordered ? 'Received' : 'Partial'));
		}

		$fuelExpenseQuery = function () use ($userId, $branchId, $startDate, $endDate, $showFuel) {
			$query = DB::table('teves_cashiers_report_p3 as expense')
				->join('teves_cashiers_report as report', 'report.cashiers_report_id', '=', 'expense.cashiers_report_id')
				->whereNull('expense.deleted_at')->whereNull('report.deleted_at')
				->where('report.cashier_report_type', 'FUEL')->whereBetween('report.report_date', [$startDate, $endDate]);
			$this->applyDashboardBranchAccess($query, 'report.teves_branch', $userId);
			return $query->when($branchId !== 'All', fn ($q) => $q->where('report.teves_branch', $branchId))
				->when(!$showFuel, fn ($q) => $q->whereRaw('1 = 0'));
		};
		$lpgExpenseQuery = function () use ($userId, $branchId, $startDate, $endDate, $showLpg) {
			$query = DB::table('teves_cashiers_report_lpg_expenses as expense')
				->join('teves_cashiers_report as report', 'report.cashiers_report_id', '=', 'expense.cashiers_report_id')
				->whereNull('expense.deleted_at')->whereNull('report.deleted_at')
				->where('report.cashier_report_type', 'LPG')->whereBetween('report.report_date', [$startDate, $endDate]);
			$this->applyDashboardBranchAccess($query, 'report.teves_branch', $userId);
			return $query->when($branchId !== 'All', fn ($q) => $q->where('report.teves_branch', $branchId))
				->when(!$showLpg, fn ($q) => $q->whereRaw('1 = 0'));
		};

		$fuelCashOutQuery = $fuelExpenseQuery()->where('expense.miscellaneous_items_type', 'CASHOUT');
		$fuelOtherQuery = $fuelExpenseQuery()->where('expense.miscellaneous_items_type', 'OTHERS');
		$lpgExpensesQuery = $lpgExpenseQuery()->whereIn('expense.expense_type', ['OPEX', 'NON-OPEX']);
		$fuelCashOut = (float) (clone $fuelCashOutQuery)->sum('expense.order_total_amount');
		$fuelOther = (float) (clone $fuelOtherQuery)->sum('expense.order_total_amount');
		$fuelCashOutCount = (int) (clone $fuelCashOutQuery)->count('expense.cashiers_report_p3_id');
		$lpgByType = (clone $lpgExpensesQuery)->selectRaw('expense.expense_type, COUNT(*) as entry_count, SUM(expense.amount) as amount')
			->groupBy('expense.expense_type')->get()->keyBy('expense_type');
		$lpgOpex = (float) ($lpgByType->get('OPEX')->amount ?? 0);
		$lpgNonOpex = (float) ($lpgByType->get('NON-OPEX')->amount ?? 0);
		$lpgExpenseCount = (int) $lpgByType->sum('entry_count');
		$lpgExpenses = $lpgOpex + $lpgNonOpex;

		$fuelTrend = (clone $fuelCashOutQuery)->selectRaw('report.report_date as day, SUM(expense.order_total_amount) as amount')
			->groupBy('report.report_date')->orderBy('report.report_date')->get();
		$lpgTrend = (clone $lpgExpensesQuery)->selectRaw('report.report_date as day, expense.expense_type, SUM(expense.amount) as amount')
			->groupBy('report.report_date', 'expense.expense_type')->orderBy('report.report_date')->get();
		$trendByDay = [];
		foreach ($fuelTrend as $row) $trendByDay[$row->day]['fuel'] = (float) $row->amount;
		foreach ($lpgTrend as $row) {
			$key = $row->expense_type === 'NON-OPEX' ? 'lpg_non_opex' : 'lpg_opex';
			$trendByDay[$row->day][$key] = (float) $row->amount;
		}
		$trend = [];
		foreach (\Carbon\CarbonPeriod::create($startDate, $endDate) as $day) {
			$key = $day->toDateString();
			$trend[] = [
				'date' => $key,
				'fuel' => $trendByDay[$key]['fuel'] ?? 0,
				'lpg_opex' => $trendByDay[$key]['lpg_opex'] ?? 0,
				'lpg_non_opex' => $trendByDay[$key]['lpg_non_opex'] ?? 0,
			];
		}

		$expenseBreakdown = [];
		if ($showFuel) $expenseBreakdown[] = ['label' => 'Fuel cash out', 'source' => 'Fuel', 'amount' => $fuelCashOut, 'entries' => $fuelCashOutCount, 'excluded' => false];
		if ($showLpg) {
			$expenseBreakdown[] = ['label' => 'LPG OPEX', 'source' => 'LPG', 'amount' => $lpgOpex, 'entries' => (int) ($lpgByType->get('OPEX')->entry_count ?? 0), 'excluded' => false];
			$expenseBreakdown[] = ['label' => 'LPG Non-OPEX', 'source' => 'LPG', 'amount' => $lpgNonOpex, 'entries' => (int) ($lpgByType->get('NON-OPEX')->entry_count ?? 0), 'excluded' => false];
		}
		if ($showFuel) $expenseBreakdown[] = ['label' => 'Fuel OTHERS', 'source' => 'Fuel', 'amount' => $fuelOther, 'entries' => (int) (clone $fuelOtherQuery)->count('expense.cashiers_report_p3_id'), 'excluded' => true];

		return view('dashboard.procurement_expenses', [
			'data' => $user, 'title' => 'Procurement & Expenses', 'branches' => $branches,
			'branch_id' => $branchId, 'source' => $source, 'start_date' => $startDate, 'end_date' => $endDate,
			'purchase_order_count' => $purchaseOrderCount, 'purchase_order_amount' => $purchaseOrderAmount,
			'open_to_receive_count' => $openToReceiveCount, 'open_to_receive_amount' => $openToReceiveAmount,
			'purchase_order_pipeline' => $purchaseOrderPipeline, 'recent_purchase_orders' => $recentPurchaseOrders,
			'fuel_cash_out' => $fuelCashOut, 'fuel_cash_out_count' => $fuelCashOutCount,
			'fuel_other' => $fuelOther, 'lpg_opex' => $lpgOpex, 'lpg_non_opex' => $lpgNonOpex,
			'lpg_expenses' => $lpgExpenses, 'lpg_expense_count' => $lpgExpenseCount,
			'expense_breakdown' => $expenseBreakdown, 'trend' => $trend,
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
