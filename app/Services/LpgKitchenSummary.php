<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LpgKitchenSummary
{
    public const MODES = ['BEGINNING', 'DELIVERY', 'BO_REPLACEMENT', 'RETURN', 'DAMAGED', 'SWAP', 'ON_LOAN', 'CONSIGNMENT', 'RAFFLE', 'WALK_IN', 'TOTAL_SOLD'];

    public function records(int $reportId): Collection
    {
        $records = app(LpgSellOutSummary::class)->records($reportId, true)->map(function ($entry) {
            if ($entry['kind'] === 'sales' && $entry['mode'] !== 'RETURN') {
                $entry['mode'] = $entry['transaction_type'] === 'Cash Sales' ? 'WALK_IN' : 'SALES_ORDER';
            } elseif ($entry['kind'] === 'expense') {
                $entry['mode'] = 'RAFFLE';
            }
            return $entry;
        });

        $swaps = DB::table('teves_cashiers_report_lpg_swapping as swap')
            ->join('teves_product_table as product', 'product.product_id', '=', 'swap.replaced_product_idx')
            ->leftJoin('teves_client_table as client', 'client.client_id', '=', 'swap.client_idx')
            ->where('swap.cashiers_report_id', $reportId)->whereNull('swap.deleted_at')
            ->whereIn('product.product_id', LpgKitchenProducts::ids())
            ->whereNotIn('product.product_id', LpgRegulatorProducts::ids())
            ->orderBy('swap.cashiers_report_lpg_swapping_id')->get(['swap.*', 'client.client_name']);
        foreach ($swaps as $row) {
            $records->push($this->entry('SWAP', 'swap', $row->cashiers_report_lpg_swapping_id, $row->replaced_product_idx, $row->replaced_quantity, $row->client_name, null, $row->remarks));
        }

        $movements = DB::table('teves_cashiers_report_lpg_kitchen_movements as movement')
            ->join('teves_product_table as product', 'product.product_id', '=', 'movement.product_idx')
            ->leftJoin('teves_client_table as client', 'client.client_id', '=', 'movement.client_idx')
            ->where('movement.cashiers_report_id', $reportId)->whereNull('movement.deleted_at')
            ->whereIn('product.product_id', LpgKitchenProducts::ids())
            ->whereNotIn('product.product_id', LpgRegulatorProducts::ids())
            ->orderBy('movement.cashiers_report_lpg_kitchen_movement_id')->get(['movement.*', 'client.client_name']);
        foreach ($movements as $row) {
            $entry = $this->entry($row->movement_type, 'kitchen', $row->cashiers_report_lpg_kitchen_movement_id, $row->product_idx, $row->quantity, $row->client_name, $row->reference_no, $row->remarks);
            $entry['client_idx'] = $row->client_idx;
            $records->push($entry);
        }

        $products = DB::table('teves_product_table as product')
            ->leftJoin('teves_product_main_table as main', 'main.product_main_id', '=', 'product.product_main_idx')
            ->whereIn('product.product_id', $records->pluck('product_idx')->unique()->all())
            ->get(['product.product_id', 'product.product_name', 'product.product_unit_measurement', 'main.product_name as main_product_name'])->keyBy('product_id');
        return $records->map(function ($entry) use ($products) {
            $product = $products->get($entry['product_idx']);
            $entry['product_name'] = $product->product_name ?? 'Unavailable product';
            $entry['product_unit_measurement'] = $product->product_unit_measurement ?? null;
            $entry['main_product_name'] = $product->main_product_name ?? 'Kitchen Products & Accessories';
            return $entry;
        })->values();
    }

    public function rows(Collection $records): Collection
    {
        return $records->groupBy('product_idx')->map(function ($entries) {
            $first = $entries->first();
            $row = array_intersect_key($first, array_flip(['product_idx', 'product_name', 'product_unit_measurement', 'main_product_name']));
            foreach (array_diff(self::MODES, ['TOTAL_SOLD']) as $mode) {
                $source = $entries->where('mode', $mode);
                $row[strtolower($mode) . '_quantity'] = round($source->sum('quantity'), 2);
                $row[strtolower($mode) . '_count'] = $source->count();
            }
            $sales = $entries->whereIn('mode', ['WALK_IN', 'SALES_ORDER']);
            $row['total_sold_quantity'] = round($sales->sum('quantity'), 2);
            $row['total_sales'] = round($sales->sum('amount'), 2);
            $row['net_sales'] = round($sales->sum('net_amount'), 2);
            $prices = $sales->pluck('unit_price')->unique()->values();
            $row['unit_price'] = $prices->count() === 1 ? $prices->first() : null;
            $row['mixed_prices'] = $prices->count() > 1;
            // Walk-in is already part of total sold; never subtract it again.
            $row['ending_quantity'] = $row['beginning_count'] ? round(
                $row['beginning_quantity'] + $row['delivery_quantity'] + $row['damaged_quantity'] + $row['bo_replacement_quantity'] + $row['return_quantity']
                - $row['swap_quantity'] - $row['on_loan_quantity'] - $row['consignment_quantity']
                - $row['raffle_quantity'] - $row['total_sold_quantity'], 2
            ) : null;
            return $row;
        })->sortBy(function ($row) { return $row['main_product_name'] . ' ' . $row['product_name']; }, SORT_NATURAL | SORT_FLAG_CASE)->values();
    }

    private function entry($mode, $kind, $id, $productId, $quantity, $account, $reference, $remarks): array
    {
        return ['mode' => $mode, 'kind' => $kind, 'record_id' => (int) $id, 'product_idx' => (int) $productId,
            'quantity' => (float) $quantity, 'account' => $account, 'reference' => $reference, 'remarks' => $remarks,
            'unit_price' => null, 'amount' => null, 'net_amount' => null];
    }
}
