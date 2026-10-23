<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class LpgRegulatorProducts
{
    public static function ids(): array
    {
        $products = array_map('intval', config('lpg_inventory.regulator_product_ids', []));
        $mainProducts = array_map('intval', config('lpg_inventory.regulator_main_product_ids', []));
        return DB::table('teves_product_table')->where(function ($query) use ($products, $mainProducts) {
            $query->whereIn('product_id', $products)->orWhereIn('product_main_idx', $mainProducts);
        })->pluck('product_id')->map(function ($id) { return (int) $id; })->all();
    }

    public static function isProduct(int $productId): bool
    {
        return in_array($productId, self::ids(), true);
    }
}
