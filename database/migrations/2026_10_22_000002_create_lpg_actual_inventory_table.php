<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teves_cashiers_report_lpg_actual_inventory', function (Blueprint $table) {
            $table->increments('cashiers_report_lpg_actual_inventory_id');
            $table->unsignedInteger('cashiers_report_id');
            $table->integer('product_idx');
            $table->decimal('actual_quantity', 12, 2);
            $table->text('remarks')->nullable();
            $table->dateTime('created_at');
            $table->integer('created_by_user_idx')->default(0);
            $table->dateTime('updated_at');
            $table->integer('updated_by_user_idx')->default(0);
            $table->unique(['cashiers_report_id', 'product_idx'], 'lpg_actual_report_product_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teves_cashiers_report_lpg_actual_inventory');
    }
};
