<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teves_client_table', function (Blueprint $table) {
            $table->date('client_birthday')
                ->nullable()
                ->after('client_email_address');
        });

        Schema::table('teves_client_table', function (Blueprint $table) {
            $table->dropColumn('client_age');
        });
    }

    public function down(): void
    {
        Schema::table('teves_client_table', function (Blueprint $table) {
            $table->integer('client_age')
                ->nullable()
                ->after('client_email_address');
        });

        Schema::table('teves_client_table', function (Blueprint $table) {
            $table->dropColumn('client_birthday');
        });
    }
};