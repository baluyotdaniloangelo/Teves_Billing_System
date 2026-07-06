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

            $table->unsignedBigInteger('sales_agent_idx')
                  ->nullable()
                  ->after('referred_by_idx');
/*
            $table->foreign('sales_agent_idx')
                  ->references('sales_agent_idx')
                  ->on('teves_sales_agent');*/
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teves_client_table', function (Blueprint $table) {

            /*$table->dropForeign(['sales_agent_idx']);*/

            $table->dropColumn('sales_agent_idx');
        });
    }
};