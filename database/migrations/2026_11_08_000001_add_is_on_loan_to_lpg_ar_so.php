<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teves_cashiers_report_lpg_ar_so', function (Blueprint $table) {
            $table->boolean('is_on_loan')->default(false)->after('sale_stock_type');
        });
    }

    public function down(): void
    {
        Schema::table('teves_cashiers_report_lpg_ar_so', function (Blueprint $table) {
            $table->dropColumn('is_on_loan');
        });
    }
};
