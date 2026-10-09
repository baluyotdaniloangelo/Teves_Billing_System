<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teves_cashiers_report_lpg_expenses', function (Blueprint $table) {
            $table->increments('cashiers_report_lpg_expenses_id');
            $table->integer('cashiers_report_id');
            $table->integer('client_idx')->nullable();
            $table->string('expense_type', 100)->nullable();
            $table->text('purpose')->nullable();
            $table->integer('product_idx')->nullable();
            $table->string('item_description', 255)->nullable();
            $table->double('order_quantity')->nullable();
            $table->double('unit_price')->nullable()->default(0);
            $table->decimal('amount', 12, 2)->default(0);
            $table->text('remarks')->nullable();

            $table->dateTime('created_at');
            $table->integer('created_by_user_idx')->default(0);
            $table->dateTime('updated_at');
            $table->integer('updated_by_user_idx')->default(0);
            $table->dateTime('deleted_at')->nullable();
            $table->integer('deleted_by_user_id')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teves_cashiers_report_lpg_expenses');
    }
};
