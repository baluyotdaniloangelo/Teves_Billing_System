<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LpgSellOutSummary
{
    public const MODES = ['BEGINNING', 'DELIVERY', 'BO_REPLACEMENT', 'BO_FROM_CUSTOMER', 'BO_DURING_DELIVERY', 'BO_RETURNED_TO_SUPPLIER', 'RETURN', 'ON_LOAN', 'REFILL_SWAP', 'CYLINDER_WITH_CONTENT', 'UNCLASSIFIED', 'EXPENSE'];

    public function records(int $reportId, bool $kitchen = false): Collection
    {
        $records = collect();
        $inventory = DB::table('teves_cashiers_report_lpg_inventory as i')
            ->leftJoin('teves_supplier_table as supplier', 'supplier.supplier_id', '=', 'i.supplier_idx')
            ->leftJoin('teves_client_table as client', 'client.client_id', '=', 'i.client_idx')
            ->where('i.cashiers_report_id', $reportId)->whereNull('i.deleted_at')
            ->whereIn('i.inventory_mode', ['BEGINNING', 'DELIVERY', 'BO_REPLACEMENT', 'BO_FROM_CUSTOMER', 'BO_DURING_DELIVERY', 'BO_RETURNED_TO_SUPPLIER'])
            ->orderBy('i.cashiers_report_lpg_inventory_id')->get(['i.*', 'supplier.supplier_name', 'client.client_name']);
        foreach ($inventory as $row) {
            $reference = $row->inventory_mode === 'BEGINNING' ? $row->audit_date : $row->delivery_reference_no;
            $entry = $this->entry($row->inventory_mode, 'inventory', $row->cashiers_report_lpg_inventory_id, $row->product_idx, $row->quantity, $row->inventory_mode === 'BO_FROM_CUSTOMER' ? $row->client_name : $row->supplier_name, $reference, $row->remarks);
            if ($row->inventory_mode === 'BEGINNING') {
                $entry['bo_returned_customer_quantity'] = (float) ($row->bo_returned_customer_quantity ?? 0);
                $entry['bo_during_delivery_quantity'] = (float) ($row->bo_during_delivery_quantity ?? 0);
                $entry['audit_date'] = $row->audit_date ?? null;
            }
            $records->push($entry);
        }

        $sales = DB::table('teves_cashiers_report_lpg_ar_so as s')
            ->leftJoin('teves_client_table as client', 'client.client_id', '=', 's.client_idx')
            ->where('s.cashiers_report_id', $reportId)->whereNull('s.deleted_at')
            ->whereIn('s.item_description', ['Sales Order', 'Cash Sales', 'Return'])
            ->where(function ($query) { $query->whereNull('s.sale_stock_type')->orWhere('s.sale_stock_type', '<>', 'EMPTY_CONTAINER'); })
            ->orderBy('s.cashiers_report_lpg_ar_so_id')->get(['s.*', 'client.client_name']);
        foreach ($sales as $row) {
            $mode = $row->item_description === 'Return' ? 'RETURN' :
                (in_array($row->sale_stock_type, ['REFILL_SWAP', 'CYLINDER_WITH_CONTENT'], true) ? $row->sale_stock_type : 'UNCLASSIFIED');
            $entry = $this->entry($mode, 'sales', $row->cashiers_report_lpg_ar_so_id, $row->product_idx, $row->order_quantity, $row->client_name, $row->dr_number, $row->item_description);
            // The workbook's sell-out amounts use SRP; discounts stay visible in the sales/discount records.
            $entry['unit_price'] = (float) $row->unit_price;
            $entry['transaction_type'] = $row->item_description;
            $entry['amount'] = round((float) $row->order_quantity * (float) $row->unit_price, 2);
            $entry['net_amount'] = round((float) $row->order_quantity * ((float) $row->unit_price - (float) $row->discount_per_unit), 2);
            $records->push($entry);
        }

        $loans = DB::table('teves_cashiers_report_lpg_on_loan as loan')
            ->leftJoin('teves_client_table as client', 'client.client_id', '=', 'loan.client_idx')
            ->where('loan.cashiers_report_id', $reportId)->whereNull('loan.deleted_at')
            ->where(function ($query) {
                $query->where(function ($borrow) { $borrow->where('loan.borrow_quantity', '>', 0)->whereNotNull('loan.borrow_product_idx')->where(function ($state) { $state->whereNull('loan.borrow_stock_state')->orWhere('loan.borrow_stock_state', 'FILLED'); }); })
                    ->orWhere(function ($return) { $return->where('loan.return_quantity', '>', 0)->whereNotNull('loan.return_product_idx')->where('loan.return_stock_state', 'FILLED'); });
            })
            ->orderBy('loan.cashiers_report_lpg_on_loan_id')->get(['loan.*', 'client.client_name']);
        foreach ($loans as $row) {
            if ((float) $row->borrow_quantity > 0 && $row->borrow_stock_state !== 'EMPTY') $records->push($this->entry('ON_LOAN', 'loan', $row->cashiers_report_lpg_on_loan_id, $row->borrow_product_idx, $row->borrow_quantity, $row->client_name, $row->dr_number, $row->remarks));
            if ((float) $row->return_quantity > 0 && $row->return_stock_state === 'FILLED') $records->push($this->entry('RETURN', 'loan', $row->cashiers_report_lpg_on_loan_id, $row->return_product_idx, $row->return_quantity, $row->client_name, $row->dr_number, $row->remarks));
        }

        $expenses = DB::table('teves_cashiers_report_lpg_expenses as expense')
            ->leftJoin('teves_client_table as client', 'client.client_id', '=', 'expense.client_idx')
            ->where('expense.cashiers_report_id', $reportId)->whereNull('expense.deleted_at')
            ->where('expense.order_quantity', '>', 0)->where('expense.product_idx', '>', 0)
            ->orderBy('expense.cashiers_report_lpg_expenses_id')->get(['expense.*', 'client.client_name']);
        foreach ($expenses as $row) {
            $records->push($this->entry('EXPENSE', 'expense', $row->cashiers_report_lpg_expenses_id, $row->product_idx, $row->order_quantity, $row->client_name, $row->expense_type, trim(($row->purpose ?? '') . ' ' . ($row->remarks ?? ''))));
        }

        $products = DB::table('teves_product_table')->whereIn('product_id', $records->pluck('product_idx')->unique()->all())
            ->get(['product_id', 'product_name', 'category_idx', 'product_unit_measurement', 'product_capacity', 'product_capacity_unit'])->keyBy('product_id');
        $regulatorIds = LpgRegulatorProducts::ids();
        $kitchenProductIds = LpgKitchenProducts::ids();
        return $records->map(function ($entry) use ($products) {
            $product = $products->get($entry['product_idx']);
            $entry['product_name'] = $product->product_name ?? 'Unavailable product';
            $entry['category_idx'] = (int) ($product->category_idx ?? 0);
            $entry['product_unit_measurement'] = $product->product_unit_measurement ?? null;
            $entry['product_capacity'] = $product->product_capacity ?? null;
            $entry['product_capacity_unit'] = $product->product_capacity_unit ?? null;
            return $entry;
        })->filter(function ($entry) use ($kitchen, $regulatorIds, $kitchenProductIds) {
            if (in_array($entry['product_idx'], $regulatorIds, true)) return false;
            return $kitchen
                ? in_array($entry['product_idx'], $kitchenProductIds, true)
                : in_array($entry['category_idx'], LpgEmptyInventorySummary::categoryIds(), true);
        })->values();
    }

    public function rows(Collection $records, ?int $reportId = null): Collection
    {
        $openings = $reportId ? $this->openingBalances($reportId) : collect();
        return $records->groupBy('product_idx')->map(function ($entries, $productId) use ($openings) {
            $first = $entries->first();
            $row = array_intersect_key($first, array_flip(['product_idx', 'product_name', 'product_unit_measurement', 'product_capacity', 'product_capacity_unit']));
            foreach (self::MODES as $mode) {
                $source = $entries->where('mode', $mode);
                $key = strtolower($mode);
                $row[$key . '_quantity'] = round($source->sum('quantity'), 2);
                $row[$key . '_count'] = $source->count();
                if (in_array($mode, ['REFILL_SWAP', 'CYLINDER_WITH_CONTENT', 'UNCLASSIFIED'], true)) {
                    $prices = $source->pluck('unit_price')->unique()->values();
                    $row[$key . '_unit_price'] = $prices->count() === 1 ? $prices->first() : null;
                    $row[$key . '_mixed_prices'] = $prices->count() > 1;
                    $row[$key . '_amount'] = round($source->sum('amount'), 2);
                }
            }
            $sales = $entries->whereIn('mode', ['REFILL_SWAP', 'CYLINDER_WITH_CONTENT', 'UNCLASSIFIED']);
            $row['total_sales'] = round($sales->sum('amount'), 2);
            $row['net_sales'] = round($sales->sum('net_amount'), 2);
            $beginning = $entries->where('mode', 'BEGINNING');
            $row['beginning_filled_quantity'] = round($beginning->sum('quantity'), 2);
            $row['beginning_bo_returned_customer_quantity'] = round($beginning->sum('bo_returned_customer_quantity'), 2);
            $row['beginning_bo_during_delivery_quantity'] = round($beginning->sum('bo_during_delivery_quantity'), 2);
            $row['audit_date'] = $beginning->pluck('audit_date')->filter()->first();
            $opening = $beginning->isNotEmpty() ? [
                'filled' => (float) $beginning->sum('quantity'),
                'bo_customer' => (float) $beginning->sum('bo_returned_customer_quantity'),
                'bo_delivery' => (float) $beginning->sum('bo_during_delivery_quantity'),
                'audit_date' => $beginning->pluck('audit_date')->filter()->first(),
            ] : $openings->get((int) $productId);
            $row['beginning_count'] = $opening ? 1 : 0;
            $row['beginning_quantity'] = $row['beginning_filled_quantity'] = $opening['filled'] ?? 0;
            $row['beginning_bo_returned_customer_quantity'] = $opening['bo_customer'] ?? 0;
            $row['beginning_bo_during_delivery_quantity'] = $opening['bo_delivery'] ?? 0;
            $row['audit_date'] = $opening['audit_date'] ?? null;
            $row['ending_quantity'] = $opening ? round(
                $opening['filled'] + $row['delivery_quantity'] + $row['bo_replacement_quantity'] + $row['return_quantity']
                - $row['on_loan_quantity'] - $sales->sum('quantity') - $row['expense_quantity'], 2
            ) : null;
            $customerBo = $row['beginning_bo_returned_customer_quantity'] + $row['bo_from_customer_quantity'];
            $deliveryBo = $row['beginning_bo_during_delivery_quantity'] + $row['bo_during_delivery_quantity'];
            $boDeductions = $row['bo_replacement_quantity'] + $row['bo_returned_to_supplier_quantity'];
            $deliveryDeduction = min($deliveryBo, $boDeductions);
            $row['theoretical_bo_during_delivery_quantity'] = round(max(0, $deliveryBo - $deliveryDeduction), 2);
            $row['theoretical_bo_returned_customer_quantity'] = round(max(0, $customerBo - max(0, $boDeductions - $deliveryDeduction)), 2);
            $row['theoretical_filled_quantity'] = $row['ending_quantity'];
            $row['actual_filled_quantity'] = $row['theoretical_filled_quantity'];
            $row['actual_bo_returned_customer_quantity'] = $row['theoretical_bo_returned_customer_quantity'];
            $row['actual_bo_during_delivery_quantity'] = $row['theoretical_bo_during_delivery_quantity'];
            return $row;
        })->sortBy('product_name', SORT_NATURAL | SORT_FLAG_CASE)->values();
    }

    /** Carry forward the latest starting audit through all earlier LPG shifts in this branch. */
    public function openingBalances(int $reportId): Collection
    {
        $current = DB::table('teves_cashiers_report')->where('cashiers_report_id', $reportId)
            ->where('cashier_report_type', 'LPG')->whereNull('deleted_at')
            ->first(['cashiers_report_id', 'report_date', 'teves_branch']);
        if (!$current) return collect();

        $audits = DB::table('teves_cashiers_report_lpg_starting_audit_items as item')
            ->join('teves_cashiers_report_lpg_starting_audits as audit', 'audit.audit_id', '=', 'item.audit_id')
            ->join('teves_cashiers_report as audit_report', 'audit_report.cashiers_report_id', '=', 'audit.report_id')
            ->where('audit.status', 'APPROVED')
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
            ->get(['item.product_idx', 'item.filled_quantity as quantity', 'item.bo_returned_customer_quantity', 'item.bo_during_delivery_quantity', 'audit.audit_date', 'audit.report_id as cashiers_report_id']);
        $latest = $audits->unique('product_idx')->keyBy('product_idx');
        if ($latest->isEmpty()) return collect();

        $earliestAuditDate = $latest->min('audit_date');
        $priorReports = DB::table('teves_cashiers_report')->where('cashier_report_type', 'LPG')->whereNull('deleted_at')
            ->where('teves_branch', $current->teves_branch)
            ->whereDate('report_date', '>=', $earliestAuditDate)
            ->where(function ($query) use ($current) {
                $query->whereDate('report_date', '<', $current->report_date)
                    ->orWhere(function ($sameDate) use ($current) {
                        $sameDate->whereDate('report_date', '=', $current->report_date)
                            ->where('cashiers_report_id', '<', $current->cashiers_report_id);
                    });
            })
            ->orderBy('report_date')->orderBy('cashiers_report_id')->get(['cashiers_report_id', 'report_date']);

        $balances = $latest->map(function ($audit) {
            return ['filled' => (float) $audit->quantity, 'bo_customer' => (float) $audit->bo_returned_customer_quantity,
                'bo_delivery' => (float) $audit->bo_during_delivery_quantity, 'audit_date' => $audit->audit_date,
                'audit_report_id' => (int) $audit->cashiers_report_id];
        });
        foreach ($priorReports as $priorReport) {
            $priorReportId = (int) $priorReport->cashiers_report_id;
            foreach ($this->records((int) $priorReportId) as $entry) {
                $productId = (int) $entry['product_idx'];
                if (!$balances->has($productId)) continue;
                $balance = $balances->get($productId);
                $priorDate = substr((string) $priorReport->report_date, 0, 10);
                $auditDate = substr((string) $balance['audit_date'], 0, 10);
                if ($priorDate < $auditDate || ($priorDate === $auditDate && $priorReportId < $balance['audit_report_id'])) continue;
                $quantity = (float) $entry['quantity'];
                switch ($entry['mode']) {
                    case 'DELIVERY': $balance['filled'] += $quantity; break;
                    case 'BO_REPLACEMENT': $balance['filled'] += $quantity; $this->deductBo($balance, $quantity); break;
                    case 'RETURN': $balance['filled'] += $quantity; break;
                    case 'BO_FROM_CUSTOMER': $balance['bo_customer'] += $quantity; break;
                    case 'BO_DURING_DELIVERY': $balance['bo_delivery'] += $quantity; break;
                    case 'BO_RETURNED_TO_SUPPLIER': $this->deductBo($balance, $quantity); break;
                    case 'ON_LOAN': $balance['filled'] -= $quantity; break;
                    case 'REFILL_SWAP': case 'CYLINDER_WITH_CONTENT': case 'UNCLASSIFIED': case 'EXPENSE': $balance['filled'] -= $quantity; break;
                }
                $balances->put($productId, $balance);
            }
        }
        return $balances;
    }

    private function deductBo(array &$balance, float $quantity): void
    {
        $fromDelivery = min((float) $balance['bo_delivery'], $quantity);
        $balance['bo_delivery'] -= $fromDelivery;
        $balance['bo_customer'] -= min((float) $balance['bo_customer'], max(0, $quantity - $fromDelivery));
    }

    private function entry($mode, $kind, $id, $productId, $quantity, $account, $reference, $remarks): array
    {
        return ['mode' => $mode, 'kind' => $kind, 'record_id' => (int) $id, 'product_idx' => (int) $productId,
            'quantity' => (float) $quantity, 'account' => $account, 'reference' => $reference, 'remarks' => $remarks,
            'unit_price' => null, 'amount' => null, 'net_amount' => null];
    }
}
