<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LpgEmptyReceipt
{
    public static function rules(Request $request, bool $isKitchen): array
    {
        $refill = !$isKitchen && $request->item_description !== 'Return' && $request->sale_stock_type === 'REFILL_SWAP';
        $linked = $refill && $request->filled('empty_swapping_idx');
        $quantity = (int) $request->input('empty_received_quantity', 0);
        $rules = [
            'empty_swapping_idx' => ['nullable', 'integer', Rule::exists('teves_cashiers_report_lpg_swapping', 'cashiers_report_lpg_swapping_id')->where(function ($query) use ($request) {
                $query->where('cashiers_report_id', $request->cashiers_report_id)->where('client_idx', $request->client_idx)->where('received_stock_state', 'EMPTY')->whereNull('deleted_at');
                $query->whereIn('received_product_idx', DB::table('teves_product_table')->select('product_id')->whereIn('category_idx', LpgEmptyInventorySummary::categoryIds())->whereNotIn('product_id', LpgRegulatorProducts::ids()));
            })],
            'empty_received_quantity' => [$refill && !$linked ? 'required' : 'nullable', 'integer', 'min:0', 'max:2147483647'],
            'empty_received_product_idx' => [$refill && !$linked && $quantity > 0 ? 'required' : 'nullable', 'integer', Rule::exists('teves_product_table', 'product_id')->where(function ($query) {
                $query->whereIn('category_idx', LpgEmptyInventorySummary::categoryIds())->whereNotIn('product_id', LpgRegulatorProducts::ids())->whereNull('deleted_at');
            })],
        ];
        if (!$isKitchen && $request->sale_stock_type === 'EMPTY_CONTAINER') $rules['unit_price'] = ['required', 'numeric', 'min:0', 'max:9999999999.99'];
        return $rules;
    }

    public static function validateQuantity($validator, Request $request): void
    {
        $quantity = (float) $request->order_quantity;
        if ($request->sale_stock_type === 'EMPTY_CONTAINER' && ($quantity !== floor($quantity) || $quantity > 2147483647)) {
            $validator->errors()->add('order_quantity', 'Empty containers must be a whole number of pieces, up to 2,147,483,647.');
        }
    }

    public static function fill($record, array $data): void
    {
        $refill = ($data['item_description'] ?? null) !== 'Return' && ($data['sale_stock_type'] ?? null) === 'REFILL_SWAP';
        $record->empty_swapping_idx = $refill ? ($data['empty_swapping_idx'] ?? null) : null;
        $record->empty_received_product_idx = $refill && !$record->empty_swapping_idx ? ($data['empty_received_product_idx'] ?? null) : null;
        $record->empty_received_quantity = $refill && !$record->empty_swapping_idx ? ($data['empty_received_quantity'] ?? null) : null;
    }
}
