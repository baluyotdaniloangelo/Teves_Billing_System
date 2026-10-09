<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teves_cashiers_report_lpg_ar', function (Blueprint $table) {
            $table->increments('cashiers_report_lpg_ar_id');
            $table->unsignedInteger('cashiers_report_id');
            $table->date('ar_date');
			$table->integer('client_idx')->default(0);
            $table->string('dr_number', 100)->default('');
            $table->text('remarks')->nullable();
            $table->decimal('amount_received', 12, 2)->default(0);
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
        Schema::dropIfExists('teves_cashiers_report_lpg_ar');
    }
};
