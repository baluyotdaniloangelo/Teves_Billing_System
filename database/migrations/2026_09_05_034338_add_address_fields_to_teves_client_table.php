<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('teves_client_table', function (Blueprint $table) {
            $table->string('client_house_number', 100)
                ->nullable()
                ->after('client_address');

            $table->string('client_street', 255)
                ->nullable()
                ->after('client_house_number');

            $table->string('client_subdivision', 255)
                ->nullable()
                ->after('client_street');

            $table->string('client_barangay', 255)
                ->nullable()
                ->after('client_subdivision');

            $table->string('client_city', 255)
                ->nullable()
                ->after('client_barangay');

            $table->string('client_province', 255)
                ->nullable()
                ->after('client_city');

            $table->string('client_country', 100)
                ->nullable()
                ->default('Philippines')
                ->after('client_province');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teves_client_table', function (Blueprint $table) {
            $table->dropColumn([
                'client_house_number',
                'client_street',
                'client_subdivision',
                'client_barangay',
                'client_city',
                'client_province',
                'client_country',
            ]);
        });
    }
};