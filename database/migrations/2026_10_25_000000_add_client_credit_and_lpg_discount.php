<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClientCreditAndLpgDiscount extends Migration
{
    public function up()
    {
        Schema::table('teves_client_table', function (Blueprint $table) {
            $table->decimal('client_credit_limit', 12, 2)->nullable();
            $table->decimal('client_lpg_discount_amount', 12, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('teves_client_table', function (Blueprint $table) {
            $table->dropColumn(['client_credit_limit', 'client_lpg_discount_amount']);
        });
    }
}
