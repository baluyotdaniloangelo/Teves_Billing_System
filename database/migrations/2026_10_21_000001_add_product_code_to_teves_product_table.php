<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teves_product_table', function (Blueprint $table) {
            $table->string('product_code', 32)->nullable()->after('product_id');
        });

        $products = DB::table('teves_product_table as product')
            ->leftJoin('teves_product_category as category', 'product.category_idx', '=', 'category.category_id')
            ->orderBy('product.product_id')
            ->get(['product.product_id', 'category.category_code']);

        foreach ($products as $product) {
            $categoryCode = strtoupper((string) ($product->category_code ?? 'UNK'));
            if (!preg_match('/^[A-Z]{3}$/', $categoryCode)) {
                $categoryCode = 'UNK';
            }

            DB::table('teves_product_table')
                ->where('product_id', $product->product_id)
                ->update([
                    'product_code' => $categoryCode . ((int) $product->product_id + 1000),
                ]);
        }

        Schema::table('teves_product_table', function (Blueprint $table) {
            $table->unique('product_code');
        });
    }

    public function down(): void
    {
        Schema::table('teves_product_table', function (Blueprint $table) {
            $table->dropUnique('teves_product_table_product_code_unique');
            $table->dropColumn('product_code');
        });
    }
};