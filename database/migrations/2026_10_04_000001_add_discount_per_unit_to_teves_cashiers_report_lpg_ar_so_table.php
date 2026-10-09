<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teves_cashiers_report_lpg_ar_so', function (Blueprint $table) {
            $table->double('discount_per_unit')->default(0)->after('unit_price');
        });
    }

    public function down(): void
    {
        Schema::table('teves_cashiers_report_lpg_ar_so', function (Blueprint $table) {
            $table->dropColumn('discount_per_unit');
        });
    }
};
