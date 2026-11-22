<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPostDatedCheckDetailsToReceivablePayments extends Migration
{
    public function up()
    {
        Schema::table('teves_receivable_payment', function (Blueprint $table) {
            $table->string('pdc_drawee_bank', 150)->nullable();
            $table->string('pdc_payee', 150)->nullable();
            $table->string('pdc_check_number', 80)->nullable();
            $table->date('pdc_check_date')->nullable();
        });
    }

    public function down()
    {
        Schema::table('teves_receivable_payment', function (Blueprint $table) {
            $table->dropColumn([
                'pdc_drawee_bank',
                'pdc_payee',
                'pdc_check_number',
                'pdc_check_date',
            ]);
        });
    }
}
