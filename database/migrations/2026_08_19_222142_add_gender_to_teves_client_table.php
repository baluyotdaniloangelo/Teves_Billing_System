<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGenderToTevesClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teves_client_table', function (Blueprint $table) {
			$table->enum('client_gender', ['Male', 'Female'])
				  ->nullable()
				  ->after('client_birthday');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teves_client_table', function (Blueprint $table) {
            //
        });
    }
}
