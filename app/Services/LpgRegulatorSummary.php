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
            ->whereIn('source.inventory_mode', ['BEGINNING', 'DELIVERY', 'BO_REPLACEMENT'])
            ->orderBy('source.cashiers_report_lpg_inventory_id')->get(['source.*', 'account.supplier_name']);
        foreach ($inventory as $row) {
            $records->push($this->entry($row->inventory_mode, 'inventory', $row->cashiers_report_lpg_inventory_id, $row->product_idx, $row->quantity, $row->supplier_name, $row->delivery_reference_no, $row->remarks));
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
            $records->push($this->entry($row->item_description === 'Return' ? 'RETURN' : 'SALE', 'sales', $row->cashiers_report_lpg_ar_so_id, $row->product_idx, $row->order_quantity, $row->client_name, $row->dr_number, $row->item_description));
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
        $products = DB::table('teves_product_table')->whereIn('product_id', LpgRegulatorProducts::ids())
            ->where(function ($query) use ($records) { $query->whereNull('deleted_at')->orWhereIn('product_id', $records->pluck('product_idx')->all()); })
            ->orderBy('product_name')->get(['product_id', 'product_name', 'product_unit_measurement']);
        return $products->map(function ($product) use ($records) {
            $entries = $records->where('product_idx', $product->product_id);
            $row = ['product_idx' => (int) $product->product_id, 'product_name' => $product->product_name, 'product_unit_measurement' => $product->product_unit_measurement];
            foreach (self::FILTERS as $filter) {
                $source = $this->sources($entries, $filter);
                $row[strtolower($filter) . '_quantity'] = round($source->sum('quantity'), 2);
                $row[strtolower($filter) . '_count'] = $source->count();
            }
            $row['ending_quantity'] = $row['beginning_count'] ? round($row['beginning_quantity'] + $row['swap_in_quantity'] + $row['other_in_quantity'] - $row['swap_out_quantity'] - $row['other_out_quantity'], 2) : null;
            return $row;
        })->values();
    }

    private function entry($mode, $kind, $id, $productId, $quantity, $account, $reference, $remarks): array
    {
        return ['mode' => $mode, 'kind' => $kind, 'record_id' => (int) $id, 'product_idx' => (int) $productId,
            'quantity' => (float) $quantity, 'account' => $account, 'reference' => $reference, 'remarks' => $remarks];
    }
}
