<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teves_cashiers_report_lpg_swapping', function (Blueprint $table) {
            $table->increments('cashiers_report_lpg_swapping_id');
            $table->unsignedInteger('cashiers_report_id');
            $table->unsignedInteger('client_idx');
            $table->unsignedInteger('received_product_idx');
            $table->double('received_quantity')->default(0);
            $table->unsignedInteger('replaced_product_idx');
            $table->double('replaced_quantity')->default(0);
            $table->text('remarks')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->integer('created_by_user_idx')->default(0);
            $table->dateTime('updated_at')->nullable();
            $table->integer('updated_by_user_idx')->default(0);
            $table->dateTime('deleted_at')->nullable();
            $table->integer('deleted_by_user_id')->default(0);

            $table->index('cashiers_report_id', 'idx_lpg_swapping_report');
            $table->index('client_idx', 'idx_lpg_swapping_client');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teves_cashiers_report_lpg_swapping');
    }
};
