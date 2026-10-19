<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teves_cashiers_report_lpg_empty_inventory', function (Blueprint $table) {
            $table->increments('cashiers_report_lpg_empty_inventory_id');
            $table->unsignedInteger('cashiers_report_id');
            $table->unsignedInteger('product_idx');
            $table->unsignedInteger('beginning_quantity')->default(0);
            $table->unsignedInteger('refill_sell_out_quantity')->default(0);
            $table->unsignedInteger('return_quantity')->default(0);
            $table->unsignedInteger('swap_in_quantity')->default(0);
            $table->unsignedInteger('from_other_branch_quantity')->default(0);
            $table->unsignedInteger('sold_quantity')->default(0);
            $table->unsignedInteger('swap_out_quantity')->default(0);
            $table->unsignedInteger('borrow_quantity')->default(0);
            $table->unsignedInteger('pull_out_branch_quantity')->default(0);
            $table->unsignedInteger('pull_out_truck_quantity')->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->integer('created_by_user_idx')->default(0);
            $table->integer('updated_by_user_idx')->default(0);
            $table->softDeletes();
            $table->integer('deleted_by_user_id')->default(0);
            $table->unique(['cashiers_report_id', 'product_idx'], 'uq_lpg_empty_report_product');
            $table->index('product_idx', 'idx_lpg_empty_product');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teves_cashiers_report_lpg_empty_inventory');
    }
};
