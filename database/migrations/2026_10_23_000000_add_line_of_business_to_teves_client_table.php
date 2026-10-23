<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLineOfBusinessToTevesClientTable extends Migration
{
    public function up()
    {
        Schema::table('teves_client_table', function (Blueprint $table) {
            $table->string('client_line_of_business', 255)->nullable();
        });
    }

    public function down()
    {
        Schema::table('teves_client_table', function (Blueprint $table) {
            $table->dropColumn('client_line_of_business');
        });
    }
}
