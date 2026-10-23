<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teves_cashiers_report_lpg_kitchen_movements', function (Blueprint $table) {
            $table->increments('cashiers_report_lpg_kitchen_movement_id');
            $table->unsignedInteger('cashiers_report_id');
            $table->string('movement_type', 20);
            $table->integer('product_idx');
            $table->integer('client_idx')->nullable();
            $table->string('reference_no', 100)->nullable();
            $table->decimal('quantity', 12, 2);
            $table->text('remarks')->nullable();
            $table->dateTime('created_at');
            $table->integer('created_by_user_idx')->default(0);
            $table->dateTime('updated_at');
            $table->integer('updated_by_user_idx')->default(0);
            $table->softDeletes();
            $table->integer('deleted_by_user_id')->default(0);
            $table->index(['cashiers_report_id', 'movement_type'], 'lpg_kitchen_report_movement_idx');
            $table->index('product_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teves_cashiers_report_lpg_kitchen_movements');
    }
};
