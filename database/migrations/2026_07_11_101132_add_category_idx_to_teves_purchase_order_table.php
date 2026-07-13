<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('teves_purchase_order_table', function (Blueprint $table) {
            $table->integer('category_idx')
                ->nullable()
                ->after('purchase_order_supplier_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teves_purchase_order_table', function (Blueprint $table) {
            $table->dropColumn('category_idx');
        });
    }
};