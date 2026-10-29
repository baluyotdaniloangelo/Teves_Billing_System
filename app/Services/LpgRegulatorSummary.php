<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LpgRegulatorSummary
{
    public const FILTERS = ['BEGINNING', 'SWAP_IN', 'SWAP_OUT', 'OTHER_IN', 'OTHER_OUT'];
    public const OTHER_IN = ['DELIVERY', 'BO_REPLACEMENT', 'LOAN_RETURN', 'RETURN'];
    public const OTHER_OUT = ['BORROW', 'SALE', 'EXPENSE'];

    public function records(int $reportId): Collection
    {
        $ids = LpgRegulatorProducts::ids();
        $records = collect();
        $inventory = DB::table('teves_cashiers_report_lpg_inventory as source')
            ->leftJoin('teves_supplier_table as account', 'account.supplier_id', '=', 'source.supplier_idx')
            ->where('source.cashiers_report_id', $reportId)->whereNull('source.deleted_at')->whereIn('source.product_idx', $ids)
            ->whereIn('source.inventory_mode', ['BEGINNING', 'DELIVERY', 'BO_REPLACEMENT', 'BO_FROM_CUSTOMER', 'BO_DURING_DELIVERY', 'BO_RETURNED_TO_SUPPLIER'])
            ->orderBy('source.cashiers_report_lpg_inventory_id')->get(['source.*', 'account.supplier_name']);
        foreach ($inventory as $row) {
            $entry = $this->entry($row->inventory_mode, 'inventory', $row->cashiers_report_lpg_inventory_id, $row->product_idx, $row->quantity, $row->supplier_name, $row->delivery_reference_no, $row->remarks);
            if ($row->inventory_mode === 'BEGINNING') {
                $entry['bo_returned_customer_quantity'] = (float) ($row->bo_returned_customer_quantity ?? 0);
                $entry['bo_during_delivery_quantity'] = (float) ($row->bo_during_delivery_quantity ?? 0);
            }
            $records->push($entry);
        }
        foreach (['swap' => 'teves_cashiers_report_lpg_swapping', 'loan' => 'teves_cashiers_report_lpg_on_loan'] as $kind => $table) {
            $sides = $kind === 'swap' ? ['received' => 'SWAP_IN', 'replaced' => 'SWAP_OUT'] : ['borrow' => 'BORROW', 'return' => 'LOAN_RETURN'];
            $key = $kind === 'swap' ? 'cashiers_report_lpg_swapping_id' : 'cashiers_report_lpg_on_loan_id';
            $rows = DB::table($table . ' as source')->leftJoin('teves_client_table as account', 'account.client_id', '=', 'source.client_idx')
                ->where('source.cashiers_report_id', $reportId)->whereNull('source.deleted_at')
                ->where(function ($query) use ($sides, $ids) {
                    foreach ($sides as $side => $mode) $query->orWhereIn('source.' . $side . '_product_idx', $ids);
                })->orderBy('source.' . $key)->get(['source.*', 'account.client_name']);
            foreach ($rows as $row) {
                foreach ($sides as $side => $mode) {
                    if (!in_array((int) $row->{$side . '_product_idx'}, $ids, true) || (float) $row->{$side . '_quantity'} <= 0) continue;
                    $records->push($this->entry($mode, $kind, $row->{$key}, $row->{$side . '_product_idx'}, $row->{$side . '_quantity'}, $row->client_name, $row->dr_number ?? null, $row->remarks));
                }
            }
        }
        $sales = DB::table('teves_cashiers_report_lpg_ar_so as source')
            ->leftJoin('teves_client_table as account', 'account.client_id', '=', 'source.client_idx')
            ->where('source.cashiers_report_id', $reportId)->whereNull('source.deleted_at')->whereIn('source.product_idx', $ids)
            ->whereIn('source.item_description', ['Sales Order', 'Cash Sales', 'Return'])
            ->orderBy('source.cashiers_report_lpg_ar_so_id')->get(['source.*', 'account.client_name']);
        foreach ($sales as $row) {
            $entry = $this->entry($row->item_description === 'Return' ? 'RETURN' : 'SALE', 'sales', $row->cashiers_report_lpg_ar_so_id, $row->product_idx, $row->order_quantity, $row->client_name, $row->dr_number, $row->item_description);
            $entry['unit_price'] = (float) $row->unit_price;
            $records->push($entry);
        }
        $expenses = DB::table('teves_cashiers_report_lpg_expenses as source')
            ->leftJoin('teves_client_table as account', 'account.client_id', '=', 'source.client_idx')
            ->where('source.cashiers_report_id', $reportId)->whereNull('source.deleted_at')->whereIn('source.product_idx', $ids)->where('source.order_quantity', '>', 0)
            ->orderBy('source.cashiers_report_lpg_expenses_id')->get(['source.*', 'account.client_name']);
        foreach ($expenses as $row) {
            $records->push($this->entry('EXPENSE', 'expense', $row->cashiers_report_lpg_expenses_id, $row->product_idx, $row->order_quantity, $row->client_name, $row->expense_type, trim(($row->purpose ?? '') . ' ' . ($row->remarks ?? ''))));
        }
        $products = DB::table('teves_product_table')->whereIn('product_id', $ids)->get(['product_id', 'product_name', 'product_unit_measurement'])->keyBy('product_id');
        return $records->map(function ($entry) use ($products) {
            $product = $products->get($entry['product_idx']);
            $entry['product_name'] = $product->product_name ?? 'Unavailable product';
            $entry['product_unit_measurement'] = $product->product_unit_measurement ?? null;
            return $entry;
        })->values();
    }

    public function sources(Collection $records, string $filter): Collection
    {
        $modes = $filter === 'OTHER_IN' ? self::OTHER_IN : ($filter === 'OTHER_OUT' ? self::OTHER_OUT : [$filter]);
        return $records->whereIn('mode', $modes)->values();
    }

    public function rows(int $reportId): Collection
    {
        $records = $this->records($reportId);
        $openingBalances = $this->openingBalances($reportId);
        $setSales = (new LpgSetComponentSales())->forReport($reportId)['quantities'];
        $products = DB::table('teves_product_table')->whereIn('product_id', LpgRegulatorProducts::ids())
            ->where(function ($query) use ($records) { $query->whereNull('deleted_at')->orWhereIn('product_id', $records->pluck('product_idx')->all()); })
            ->orderBy('product_name')->get(['product_id', 'product_name', 'product_unit_measurement', 'product_price']);
        return $products->map(function ($product) use ($records, $setSales, $openingBalances) {
            $entries = $records->where('product_idx', $product->product_id);
            $sum = fn (string $mode) => round($entries->where('mode', $mode)->sum('quantity'), 2);
            $sales = $entries->where('mode', 'SALE');
            $beginningEntries = $entries->where('mode', 'BEGINNING');
            $currentBeginning = $beginningEntries->sum(fn ($entry) =>
                (float) $entry['quantity'] + (float) ($entry['bo_returned_customer_quantity'] ?? 0) + (float) ($entry['bo_during_delivery_quantity'] ?? 0)
            );
            $opening = $beginningEntries->isNotEmpty() ? null : $openingBalances->get((int) $product->product_id);
            $row = [
                'product_idx' => (int) $product->product_id,
                'product_name' => $product->product_name,
                'product_unit_measurement' => $product->product_unit_measurement,
                'beginning_quantity' => round($beginningEntries->isNotEmpty() ? $currentBeginning : ($opening['quantity'] ?? 0), 2),
                'set_sold_quantity' => round((float) ($setSales[(int) $product->product_id] ?? 0), 2),
                'delivery_quantity' => $sum('DELIVERY'),
                'swap_quantity' => round($sum('SWAP_IN') - $sum('SWAP_OUT'), 2),
                'on_loan_quantity' => $sum('BORROW'),
                'bo_customer_quantity' => $sum('BO_FROM_CUSTOMER'),
                'bo_delivery_quantity' => $sum('BO_DURING_DELIVERY'),
                'returned_supplier_quantity' => $sum('BO_RETURNED_TO_SUPPLIER'),
                'bo_replacement_quantity' => $sum('BO_REPLACEMENT'),
                'total_sold_quantity' => round($sales->sum('quantity'), 2),
                'unit_price' => (float) $product->product_price,
                'total_sales' => round($sales->sum(fn ($entry) => (float) $entry['quantity'] * (float) ($entry['unit_price'] ?? 0)), 2),
            ];
            // Set Sold is informational because assembly already consumes component stock.
            // Ending on hand includes defective units until they are returned to the supplier.
            $row['theoretical_ending_quantity'] = round(
                $row['beginning_quantity'] + $row['delivery_quantity'] + $sum('SWAP_IN') + $sum('LOAN_RETURN')
                + $row['bo_customer_quantity'] + $row['bo_delivery_quantity'] + $row['bo_replacement_quantity']
                + $sum('RETURN') - $sum('SWAP_OUT') - $row['on_loan_quantity'] - $row['total_sold_quantity']
                - $sum('EXPENSE') - $row['returned_supplier_quantity'],
                2
            );
            $row['actual_ending_quantity'] = $row['theoretical_ending_quantity'];
            $row['variance_quantity'] = 0;
            $row['ending_quantity'] = $row['theoretical_ending_quantity'];
            return $row;
        })->values();
    }

    /** Carry forward the latest approved starting audit for regulator products in this branch. */
    private function openingBalances(int $reportId): Collection
    {
        $current = DB::table('teves_cashiers_report')->where('cashiers_report_id', $reportId)
            ->where('cashier_report_type', 'LPG')->whereNull('deleted_at')
            ->first(['cashiers_report_id', 'report_date', 'teves_branch']);
        if (!$current) return collect();

        $ids = LpgRegulatorProducts::ids();
        if (!$ids) return collect();
        $audits = DB::table('teves_cashiers_report_lpg_starting_audit_items as item')
            ->join('teves_cashiers_report_lpg_starting_audits as audit', 'audit.audit_id', '=', 'item.audit_id')
            ->join('teves_cashiers_report as audit_report', 'audit_report.cashiers_report_id', '=', 'audit.report_id')
            ->whereIn('item.product_idx', $ids)->where('audit.status', 'APPROVED')
            ->where('audit_report.cashier_report_type', 'LPG')->whereNull('audit_report.deleted_at')
            ->where('audit_report.teves_branch', $current->teves_branch)
            ->whereDate('audit.audit_date', '<=', $current->report_date)
            ->where(function ($query) use ($current) {
                $query->whereDate('audit_report.report_date', '<', $current->report_date)
                    ->orWhere(function ($sameDate) use ($current) {
                        $sameDate->whereDate('audit_report.report_date', '=', $current->report_date)
                            ->where('audit.report_id', '<=', $current->cashiers_report_id);
                    });
            })
            ->orderByDesc('audit.audit_date')->orderByDesc('audit.audit_id')
            ->get(['item.product_idx', 'item.filled_quantity', 'item.bo_returned_customer_quantity', 'item.bo_during_delivery_quantity', 'audit.audit_date', 'audit.report_id as audit_report_id']);
        $latest = $audits->unique('product_idx')->keyBy('product_idx');
        if ($latest->isEmpty()) return collect();

        $earliestAuditDate = $latest->min('audit_date');
        $priorReports = DB::table('teves_cashiers_report')->where('cashier_report_type', 'LPG')->whereNull('deleted_at')
            ->where('teves_branch', $current->teves_branch)->whereDate('report_date', '>=', $earliestAuditDate)
            ->where(function ($query) use ($current) {
                $query->whereDate('report_date', '<', $current->report_date)
                    ->orWhere(function ($sameDate) use ($current) {
                        $sameDate->whereDate('report_date', '=', $current->report_date)
                            ->where('cashiers_report_id', '<', $current->cashiers_report_id);
                    });
            })->orderBy('report_date')->orderBy('cashiers_report_id')->get(['cashiers_report_id', 'report_date']);

        $balances = $latest->map(fn ($audit) => [
            'quantity' => (float) $audit->filled_quantity + (float) $audit->bo_returned_customer_quantity + (float) $audit->bo_during_delivery_quantity,
            'audit_date' => $audit->audit_date,
            'audit_report_id' => (int) $audit->audit_report_id,
        ]);
        foreach ($priorReports as $priorReport) {
            $priorId = (int) $priorReport->cashiers_report_id;
            $priorDate = substr((string) $priorReport->report_date, 0, 10);
            foreach ($this->records($priorId) as $entry) {
                $productId = (int) $entry['product_idx'];
                if (!$balances->has($productId)) continue;
                $balance = $balances->get($productId);
                $auditDate = substr((string) $balance['audit_date'], 0, 10);
                if ($priorDate < $auditDate || ($priorDate === $auditDate && $priorId < $balance['audit_report_id'])) continue;
                $quantity = (float) $entry['quantity'];
                switch ($entry['mode']) {
                    case 'DELIVERY': case 'BO_REPLACEMENT': case 'BO_FROM_CUSTOMER': case 'BO_DURING_DELIVERY': case 'SWAP_IN': case 'LOAN_RETURN': case 'RETURN':
                        $balance['quantity'] += $quantity;
                        break;
                    case 'BO_RETURNED_TO_SUPPLIER': case 'SWAP_OUT': case 'BORROW': case 'SALE': case 'EXPENSE':
                        $balance['quantity'] -= $quantity;
                        break;
                }
                $balances->put($productId, $balance);
            }
        }
        return $balances;
    }

    private function entry($mode, $kind, $id, $productId, $quantity, $account, $reference, $remarks): array
    {
        return ['mode' => $mode, 'kind' => $kind, 'record_id' => (int) $id, 'product_idx' => (int) $productId,
            'quantity' => (float) $quantity, 'account' => $account, 'reference' => $reference, 'remarks' => $remarks];
    }
}
