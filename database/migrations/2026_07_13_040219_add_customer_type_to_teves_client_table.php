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
            $table->string('customer_type', 100)
                  ->nullable()
                  ->after('client_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teves_client_table', function (Blueprint $table) {
            $table->dropColumn('customer_type');
        });
    }
};