<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teves_cashiers_report', function (Blueprint $table) {
            $table->string('cashier_report_type', 10)
                ->default('FUEL')
                ->comment('Cashier report type: LPG or FUEL')
                ->after('total_cash_sales');
        });

        // Copy existing Column 2 values if needed, then remove the old column.
        Schema::table('teves_cashiers_report', function (Blueprint $table) {
            $table->dropColumn('Column 2');
        });
    }

    public function down(): void
    {
        Schema::table('teves_cashiers_report', function (Blueprint $table) {
            $table->integer('Column 2')
                ->default(0)
                ->after('total_cash_sales');
        });

        Schema::table('teves_cashiers_report', function (Blueprint $table) {
            $table->dropColumn('cashier_report_type');
        });
    }
};