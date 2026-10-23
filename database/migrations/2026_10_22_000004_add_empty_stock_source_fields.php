<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teves_cashiers_report_lpg_swapping', function (Blueprint $table) {
            $table->string('received_stock_state', 10)->nullable();
            $table->string('replaced_stock_state', 10)->nullable();
        });
        Schema::table('teves_cashiers_report_lpg_on_loan', function (Blueprint $table) {
            $table->string('borrow_stock_state', 10)->nullable();
            $table->string('return_stock_state', 10)->nullable();
        });
        Schema::table('teves_cashiers_report_lpg_ar_so', function (Blueprint $table) {
            $table->integer('empty_received_product_idx')->nullable();
            $table->unsignedInteger('empty_received_quantity')->nullable();
            $table->unsignedInteger('empty_swapping_idx')->nullable()->index();
        });
        Schema::table('teves_cashiers_report_lpg_empty_inventory', function (Blueprint $table) {
            // Once a field is sourced automatically, deleting its last source must leave zero.
            $table->json('auto_fields')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('teves_cashiers_report_lpg_swapping', function (Blueprint $table) { $table->dropColumn(['received_stock_state', 'replaced_stock_state']); });
        Schema::table('teves_cashiers_report_lpg_on_loan', function (Blueprint $table) { $table->dropColumn(['borrow_stock_state', 'return_stock_state']); });
        Schema::table('teves_cashiers_report_lpg_ar_so', function (Blueprint $table) { $table->dropColumn(['empty_received_product_idx', 'empty_received_quantity', 'empty_swapping_idx']); });
        Schema::table('teves_cashiers_report_lpg_empty_inventory', function (Blueprint $table) { $table->dropColumn('auto_fields'); });
    }
};
