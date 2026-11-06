<?php

namespace App\Services;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class LpgProductTracking
{
    public const SELL_OUT = 'sell_out';
    public const EMPTY_INVENTORY = 'empty_inventory';

    /** Products without saved settings retain both tracking options. */
    public static function catalogQuery(bool $includeInactive = false): Builder
    {
        $query = DB::table('teves_product_table as product')
            ->whereIn('product.category_idx', LpgEmptyInventorySummary::categoryIds())
            ->whereNotIn('product.product_id', LpgRegulatorProducts::ids());
        if (!$includeInactive) $query->whereNull('product.deleted_at');
        return $query;
    }

    public static function queryFor(string $mode, bool $includeInactive = false): Builder
    {
        $columns = [self::SELL_OUT => 'sell_out_enabled', self::EMPTY_INVENTORY => 'empty_inventory_enabled'];
        if (!isset($columns[$mode])) throw new InvalidArgumentException('Unknown LPG tracking mode.');
        $column = 'tracking.' . $columns[$mode];
        return self::catalogQuery($includeInactive)
            ->leftJoin('teves_lpg_product_tracking as tracking', 'tracking.product_idx', '=', 'product.product_id')
            ->where(function ($query) use ($column) {
                $query->whereNull($column)->orWhere($column, true);
            });
    }

    public static function ids(string $mode, bool $includeInactive = false): array
    {
        return self::queryFor($mode, $includeInactive)->pluck('product.product_id')
            ->map(function ($id) { return (int) $id; })->all();
    }

    public static function enabled(int $productId, string $mode): bool
    {
        return self::queryFor($mode)->where('product.product_id', $productId)->exists();
    }
}
