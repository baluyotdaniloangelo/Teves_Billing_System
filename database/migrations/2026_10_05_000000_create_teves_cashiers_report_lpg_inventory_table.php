<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teves_cashiers_report_lpg_inventory', function (Blueprint $table) {
            $table->increments('cashiers_report_lpg_inventory_id');
            $table->unsignedInteger('cashiers_report_id');
            $table->string('inventory_mode', 20);
            $table->unsignedInteger('product_idx');
            $table->unsignedInteger('supplier_idx')->nullable();
            $table->string('delivery_reference_no', 100)->nullable();
            $table->double('quantity')->default(0);
            $table->text('remarks')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->integer('created_by_user_idx')->default(0);
            $table->dateTime('updated_at')->nullable();
            $table->integer('updated_by_user_idx')->default(0);
            $table->dateTime('deleted_at')->nullable();
            $table->integer('deleted_by_user_id')->default(0);

            $table->index(['cashiers_report_id', 'inventory_mode'], 'idx_lpg_inventory_report_mode');
            $table->index('product_idx', 'idx_lpg_inventory_product');
            $table->index('supplier_idx', 'idx_lpg_inventory_supplier');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teves_cashiers_report_lpg_inventory');
    }
};
