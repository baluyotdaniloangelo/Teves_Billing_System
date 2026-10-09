<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teves_cashiers_report_lpg_cash', function (Blueprint $table) {
            $table->increments('cashiers_report_lpg_cash_id');
            $table->integer('cashiers_report_id')->nullable();
            $table->integer('user_idx')->nullable();

            // These fields store the quantity counted for each denomination.
            $table->double('one_thousand_deno')->nullable()->default(0);
            $table->double('five_hundred_deno')->nullable()->default(0);
            $table->double('two_hundred_deno')->nullable()->default(0);
            $table->double('one_hundred_deno')->nullable()->default(0);
            $table->double('fifty_deno')->nullable()->default(0);
            $table->double('twenty_deno')->nullable()->default(0);
            $table->double('ten_deno')->nullable()->default(0);
            $table->double('five_deno')->nullable()->default(0);
            $table->double('one_deno')->nullable()->default(0);
            $table->double('twenty_five_cent_deno')->nullable()->default(0);

            $table->double('cash_drop')->nullable()->default(0);
            $table->dateTime('created_at')->nullable();
            $table->integer('created_by_user_idx')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->integer('updated_by_user_idx')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->integer('deleted_by_user_id')->nullable();

            $table->index('cashiers_report_id', 'idx_lpg_cash_cashiers_report_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teves_cashiers_report_lpg_cash');
    }
};
