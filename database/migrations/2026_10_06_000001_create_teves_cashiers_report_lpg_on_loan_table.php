<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teves_cashiers_report_lpg_on_loan', function (Blueprint $table) {
            $table->increments('cashiers_report_lpg_on_loan_id');
            $table->unsignedInteger('cashiers_report_id');
            $table->unsignedInteger('client_idx');
            $table->string('dr_number', 100)->nullable();
            $table->unsignedInteger('borrow_product_idx')->nullable();
            $table->double('borrow_quantity')->default(0);
            $table->double('borrow_alum_quantity')->default(0);
            $table->unsignedInteger('return_product_idx')->nullable();
            $table->double('return_quantity')->default(0);
            $table->double('return_alum_quantity')->default(0);
            $table->text('remarks')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->integer('created_by_user_idx')->default(0);
            $table->dateTime('updated_at')->nullable();
            $table->integer('updated_by_user_idx')->default(0);
            $table->dateTime('deleted_at')->nullable();
            $table->integer('deleted_by_user_id')->default(0);

            $table->index('cashiers_report_id', 'idx_lpg_on_loan_report');
            $table->index('client_idx', 'idx_lpg_on_loan_client');
            $table->index('dr_number', 'idx_lpg_on_loan_dr');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teves_cashiers_report_lpg_on_loan');
    }
};
