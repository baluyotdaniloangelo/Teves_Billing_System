<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teves_client_table', function (Blueprint $table) {

            $table->string('client_first_name', 100)
                ->nullable()
                ->after('client_name');

            $table->string('client_middle_name', 100)
                ->nullable()
                ->after('client_first_name');

            $table->string('client_last_name', 100)
                ->nullable()
                ->after('client_middle_name');

            $table->string('client_name_extension', 20)
                ->nullable()
                ->after('client_last_name');

            $table->string('client_title', 20)
                ->nullable()
                ->after('client_name_extension');
        });
    }

    public function down(): void
    {
        Schema::table('teves_client_table', function (Blueprint $table) {

            $table->dropColumn([
                'client_first_name',
                'client_middle_name',
                'client_last_name',
                'client_name_extension',
                'client_title',
            ]);

        });
    }
};