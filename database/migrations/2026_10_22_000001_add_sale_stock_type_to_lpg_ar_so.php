<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teves_cashiers_report_lpg_ar_so', function (Blueprint $table) {
            // Existing transactions stay unclassified until an encoder reviews them.
            $table->string('sale_stock_type', 40)->nullable()->after('item_description');
        });
    }

    public function down(): void
    {
        Schema::table('teves_cashiers_report_lpg_ar_so', function (Blueprint $table) {
            $table->dropColumn('sale_stock_type');
        });
    }
};
