<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LpgSellOutSummary
{
    public const MODES = ['BEGINNING', 'DELIVERY', 'BO_REPLACEMENT', 'RETURN', 'ON_LOAN', 'REFILL_SWAP', 'CYLINDER_WITH_CONTENT', 'UNCLASSIFIED', 'EXPENSE'];

    public function records(int $reportId, bool $kitchen = false): Collection
    {
        $records = collect();
        $inventory = DB::table('teves_cashiers_report_lpg_inventory as i')
            ->leftJoin('teves_supplier_table as supplier', 'supplier.supplier_id', '=', 'i.supplier_idx')
            ->where('i.cashiers_report_id', $reportId)->whereNull('i.deleted_at')
            ->whereIn('i.inventory_mode', ['BEGINNING', 'DELIVERY', 'BO_REPLACEMENT'])
            ->orderBy('i.cashiers_report_lpg_inventory_id')->get(['i.*', 'supplier.supplier_name']);
        foreach ($inventory as $row) {
            $records->push($this->entry($row->inventory_mode, 'inventory', $row->cashiers_report_lpg_inventory_id, $row->product_idx, $row->quantity, $row->supplier_name, $row->delivery_reference_no, $row->remarks));
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
        return $records->map(function ($entry) use ($products) {
            $product = $products->get($entry['product_idx']);
            $entry['product_name'] = $product->product_name ?? 'Unavailable product';
            $entry['category_idx'] = (int) ($product->category_idx ?? 0);
            $entry['product_unit_measurement'] = $product->product_unit_measurement ?? null;
            $entry['product_capacity'] = $product->product_capacity ?? null;
            $entry['product_capacity_unit'] = $product->product_capacity_unit ?? null;
            return $entry;
        })->filter(function ($entry) use ($kitchen, $regulatorIds) {
            return !in_array($entry['product_idx'], $regulatorIds, true)
                && in_array($entry['category_idx'], LpgKitchenProducts::categoryIds(), true) === $kitchen;
        })->values();
    }

    public function rows(Collection $records): Collection
    {
        return $records->groupBy('product_idx')->map(function ($entries) {
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
            $row['ending_quantity'] = $row['beginning_count'] ? round(
                $row['beginning_quantity'] + $row['delivery_quantity'] + $row['return_quantity'] + $row['bo_replacement_quantity']
                - $row['on_loan_quantity'] - $sales->sum('quantity') - $row['expense_quantity'], 2
            ) : null;
            return $row;
        })->sortBy('product_name', SORT_NATURAL | SORT_FLAG_CASE)->values();
    }

    private function entry($mode, $kind, $id, $productId, $quantity, $account, $reference, $remarks): array
    {
        return ['mode' => $mode, 'kind' => $kind, 'record_id' => (int) $id, 'product_idx' => (int) $productId,
            'quantity' => (float) $quantity, 'account' => $account, 'reference' => $reference, 'remarks' => $remarks,
            'unit_price' => null, 'amount' => null, 'net_amount' => null];
    }
}
