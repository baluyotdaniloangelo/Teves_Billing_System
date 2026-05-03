<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('teves_cashiers_report_p9', function (Blueprint $table) {

            $table->id('cashiers_report_p9_id');

            // Reference
            $table->string('cashiers_report_idx')->nullable();

            // Form fields
            $table->string('cash_deposit_bank')->nullable();
            $table->date('cash_deposit_date')->nullable();
            $table->decimal('cash_deposit_amount', 15, 2);
            $table->string('cash_deposit_reference')->nullable();
            $table->text('cash_deposit_remarks')->nullable();

            // Audit fields
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('created_by_user_id')->nullable();
            $table->unsignedBigInteger('updated_by_user_id')->nullable();
            $table->unsignedBigInteger('deleted_by_user_id')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('cashiers_report_p9');
    }
};