<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContactAndAgeToTevesClientTable extends Migration
{
    public function up()
    {
        Schema::table('teves_client_table', function (Blueprint $table)
        {
            $table->string('client_contact_number', 50)
                  ->nullable()
                  ->after('client_tin');

            $table->integer('client_age')
                  ->nullable()
                  ->after('client_contact_number');
        });
    }

    public function down()
    {
        Schema::table('teves_client_table', function (Blueprint $table)
        {
            $table->dropColumn([
                'client_contact_number',
                'client_age'
            ]);
        });
    }
}