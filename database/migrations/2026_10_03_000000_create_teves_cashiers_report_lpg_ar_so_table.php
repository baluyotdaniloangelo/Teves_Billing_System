<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teves_cashiers_report_lpg_ar_so', function (Blueprint $table) {
            $table->increments('cashiers_report_lpg_ar_so_id');
            $table->integer('cashiers_report_id')->nullable();
            $table->date('ar_date')->nullable();
            $table->integer('client_idx')->nullable();
            $table->integer('product_idx')->nullable();
            $table->string('dr_number', 100)->nullable();
            $table->string('item_description', 255)->nullable()->default('n/a');
            $table->double('order_quantity')->nullable();
            $table->double('unit_price')->nullable()->default(0);
            $table->double('order_total_amount')->nullable()->default(0);

            $table->dateTime('created_at')->nullable();
            $table->integer('created_by_user_idx')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->integer('updated_by_user_idx')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->integer('deleted_by_user_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teves_cashiers_report_lpg_ar_so');
    }
};
