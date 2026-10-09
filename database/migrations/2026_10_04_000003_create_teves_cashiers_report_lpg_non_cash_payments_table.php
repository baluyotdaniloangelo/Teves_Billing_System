<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teves_cashiers_report_lpg_non_cash_payments', function (Blueprint $table) {
            $table->increments('cashiers_report_lpg_non_cash_payment_id');
            $table->integer('cashiers_report_id');
            $table->string('mode_of_payment', 100);
            $table->string('payer_name', 255)->nullable();
            $table->string('payer_number', 100)->nullable();
            $table->string('reference_no', 100)->nullable();
            $table->date('check_expiry_date')->nullable();
            $table->decimal('amount', 12, 2)->default(0);

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
        Schema::dropIfExists('teves_cashiers_report_lpg_non_cash_payments');
    }
};
