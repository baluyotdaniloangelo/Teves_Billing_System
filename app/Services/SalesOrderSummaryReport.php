<?php

namespace App\Services;

use App\Models\ClientModel;
use App\Models\SalesOrderModel;
use App\Models\TevesBranchModel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SalesOrderSummaryReport
{
    public function branches($user)
    {
        $query = TevesBranchModel::query();
        if ($user->user_branch_access_type !== 'ALL') {
            $query->whereIn('branch_id', function ($access) use ($user) {
                $access->select('branch_idx')->from('teves_user_branch_access')
                    ->where('user_idx', $user->user_id);
            });
        }
        return $query->orderBy('branch_code')->get();
    }

    public function build(Request $request, $user)
    {
        $branches = $this->branches($user);
        $request->validate([
            'company_header' => ['required', Rule::in(array_merge(['All'], $branches->pluck('branch_id')->map(function ($id) { return (string) $id; })->all()))],
            'client_idx' => ['required', function ($attribute, $value, $fail) {
                if ($value !== 'All' && !ClientModel::where('client_id', $value)->exists()) {
                    $fail('Please select a valid client.');
                }
            }],
            'start_date' => ['required', 'date_format:Y-m-d'],
            'end_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:start_date'],
        ]);

        $selected = $request->company_header === 'All' ? $branches : $branches->where('branch_id', $request->company_header)->values();
        $orders = SalesOrderModel::join('teves_client_table', 'teves_client_table.client_id', '=', 'teves_sales_order_table.sales_order_client_idx')
            ->join('teves_branch_table', 'teves_branch_table.branch_id', '=', 'teves_sales_order_table.company_header')
            ->whereIn('teves_sales_order_table.company_header', $selected->pluck('branch_id'))
            ->whereBetween('teves_sales_order_table.sales_order_date', [$request->start_date, $request->end_date])
            ->where('teves_sales_order_table.sales_order_quotation', '0');
        if ($request->client_idx !== 'All') {
            $orders->where('teves_sales_order_table.sales_order_client_idx', $request->client_idx);
        }
        $orders = $orders->orderBy('teves_branch_table.branch_code')
            ->orderBy('teves_sales_order_table.sales_order_date')->orderBy('teves_sales_order_table.sales_order_id')
            ->get([
                'teves_sales_order_table.sales_order_id', 'teves_sales_order_table.company_header',
                'teves_sales_order_table.sales_order_date', 'teves_sales_order_table.sales_order_control_number',
                'teves_sales_order_table.sales_order_payment_term', 'teves_sales_order_table.sales_order_gross_amount',
                'teves_sales_order_table.sales_order_net_amount', 'teves_sales_order_table.sales_order_withholding_tax',
                'teves_sales_order_table.sales_order_total_due', 'teves_sales_order_table.sales_order_payment_status',
                'teves_sales_order_table.sales_order_delivery_status', 'teves_client_table.client_name',
                'teves_branch_table.branch_code', 'teves_branch_table.branch_name',
            ]);
        $orders->each(function ($order, $index) {
            $order->DT_RowIndex = $index + 1;
            $order->report_withholding_amount = round((float) $order->sales_order_gross_amount * (float) $order->sales_order_withholding_tax / 100, 2);
        });
        $grouped = $orders->groupBy('company_header');
        $summary = $selected->map(function ($branch) use ($grouped) {
            return array_merge([
                'branch_id' => $branch->branch_id, 'branch_code' => $branch->branch_code,
                'branch_name' => $branch->branch_name,
            ], $this->totals($grouped->get($branch->branch_id, collect())));
        })->values();

        return [
            'data' => $orders, 'branch_summary' => $summary, 'totals' => $this->totals($orders),
            'filters' => $request->only(['company_header', 'client_idx', 'start_date', 'end_date']),
            'branch_header' => $request->company_header === 'All' ? null : $selected->first(),
            'branch_label' => $request->company_header === 'All' ? 'All Available Branches' : $selected->first()->branch_name,
            'client' => $request->client_idx === 'All' ? null : ClientModel::find($request->client_idx, ['client_id', 'client_name', 'client_address', 'client_tin']),
            'available_branch_count' => $selected->count(),
        ];
    }

    private function totals($orders)
    {
        return [
            'order_count' => $orders->count(),
            'gross_amount' => round($orders->sum('sales_order_gross_amount'), 2),
            'withholding_tax' => round($orders->sum('report_withholding_amount'), 2),
            'net_amount' => round($orders->sum('sales_order_net_amount'), 2),
            'total_due' => round($orders->sum('sales_order_total_due'), 2),
        ];
    }
}
