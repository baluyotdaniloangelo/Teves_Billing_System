<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBankIdxToCashiersReportP9Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teves_cashiers_report_p9', function (Blueprint $table) {
		$table->unsignedBigInteger('bank_idx')->nullable();
	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teves_cashiers_report_p9', function (Blueprint $table) {
            //
        });
    }
}
