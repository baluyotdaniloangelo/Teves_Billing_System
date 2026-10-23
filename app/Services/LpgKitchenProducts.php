<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class LpgKitchenProducts
{
    public static function categoryIds(): array
    {
        return array_map('intval', config('lpg_inventory.kitchen_category_ids', [4]));
    }

    public static function isProduct(int $productId): bool
    {
        return DB::table('teves_product_table')->where('product_id', $productId)
            ->whereIn('category_idx', self::categoryIds())->exists();
    }
}
