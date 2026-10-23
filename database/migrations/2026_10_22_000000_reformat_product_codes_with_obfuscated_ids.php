<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $products = DB::table('teves_product_table as product')
            ->leftJoin('teves_product_category as category', 'product.category_idx', '=', 'category.category_id')
            ->orderBy('product.product_id')
            ->get(['product.product_id', 'category.category_code']);

        // Move existing codes aside first so the unique index cannot block a code swap.
        foreach ($products as $product) {
            DB::table('teves_product_table')
                ->where('product_id', $product->product_id)
                ->update(['product_code' => '~recode~' . $product->product_id]);
        }

        $usedCodes = [];
        foreach ($products as $product) {
            $categoryCode = $this->categoryPrefix($product->category_code);
            $transformedId = (int) strrev((string) ((int) $product->product_id + 100)) + 1000;
            $baseCode = $categoryCode . $transformedId;
            $productCode = $baseCode;
            $collisionSuffix = 2;

            while (isset($usedCodes[$productCode])) {
                $productCode = $baseCode . 'X' . $collisionSuffix;
                $collisionSuffix++;
            }

            $usedCodes[$productCode] = true;
            DB::table('teves_product_table')
                ->where('product_id', $product->product_id)
                ->update(['product_code' => $productCode]);
        }
    }

    public function down(): void
    {
        $products = DB::table('teves_product_table as product')
            ->leftJoin('teves_product_category as category', 'product.category_idx', '=', 'category.category_id')
            ->orderBy('product.product_id')
            ->get(['product.product_id', 'category.category_code']);

        foreach ($products as $product) {
            DB::table('teves_product_table')
                ->where('product_id', $product->product_id)
                ->update(['product_code' => '~restore~' . $product->product_id]);
        }

        foreach ($products as $product) {
            $categoryCode = $this->categoryPrefix($product->category_code);
            $productCode = $categoryCode . ((int) $product->product_id + 1000);

            DB::table('teves_product_table')
                ->where('product_id', $product->product_id)
                ->update(['product_code' => $productCode]);
        }
    }

    private function categoryPrefix(?string $categoryCode): string
    {
        $categoryCode = strtoupper((string) $categoryCode);
        return preg_match('/^[A-Z]{3}$/', $categoryCode) ? $categoryCode : 'UNK';
    }
};