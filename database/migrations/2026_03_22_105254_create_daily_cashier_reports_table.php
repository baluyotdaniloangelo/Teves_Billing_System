<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyCashierReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teves_cashier_daily_reports', function (Blueprint $table) {
		$table->bigIncrements('daily_cashier_report_id');

		$table->date('date');
		$table->integer('branch_idx');

		$table->decimal('first_shift_total_sales', 15, 2)->default(0);
		$table->decimal('second_shift_total_sales', 15, 2)->default(0);
		$table->decimal('third_shift_total_sales', 15, 2)->default(0);
		$table->decimal('fourth_shift_total_sales', 15, 2)->default(0);
		$table->decimal('fifth_shift_total_sales', 15, 2)->default(0);
		$table->decimal('sixth_shift_total_sales', 15, 2)->default(0);

		$table->decimal('shift_total_sales_sum', 15, 2)->default(0);
		$table->decimal('daily_short_over', 15, 2)->default(0);
		$table->decimal('daily_other_sales', 15, 2)->default(0);
		$table->decimal('daily_cash_transaction', 15, 2)->default(0);
		$table->decimal('daily_fuel_sales', 15, 2)->default(0);
		$table->decimal('daily_discount', 15, 2)->default(0);
		$table->decimal('daily_cashout_other', 15, 2)->default(0);
		$table->decimal('daily_theoretical_sales', 15, 2)->default(0);
		$table->decimal('daily_non_cash_payment', 15, 2)->default(0);
		$table->decimal('daily_total_cash_sales', 15, 2)->default(0);

		$table->timestamps();

		$table->unique(['date', 'branch_idx']);
	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_cashier_reports');
    }
}
