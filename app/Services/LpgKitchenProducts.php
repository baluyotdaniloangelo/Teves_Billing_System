<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class LpgKitchenProducts
{
    public static function categoryIds(): array
    {
        return array_map('intval', config('lpg_inventory.kitchen_category_ids', [4]));
    }

    public static function ids(): array
    {
        $mainProductIds = array_map('intval', config('lpg_inventory.kitchen_main_product_ids', []));
        $productIds = array_map('intval', config('lpg_inventory.kitchen_product_ids', []));
        return DB::table('teves_product_table')
            ->whereIn('category_idx', self::categoryIds())
            ->whereNull('deleted_at')
            ->where(function ($query) use ($mainProductIds, $productIds) {
                if ($mainProductIds) $query->whereIn('product_main_idx', $mainProductIds);
                if ($productIds) $query->orWhereIn('product_id', $productIds);
                $query->orWhereExists(function ($components) {
                    $components->selectRaw('1')->from('teves_product_component_table as component')
                        ->whereColumn('component.product_idx', 'teves_product_table.product_id');
                });
            })
            ->whereNotIn('product_id', LpgRegulatorProducts::ids())
            ->pluck('product_id')->map(function ($id) { return (int) $id; })->all();
    }

    public static function isProduct(int $productId): bool
    {
        return in_array($productId, self::ids(), true);
    }
}
