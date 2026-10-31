<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teves_cashiers_report_lpg_ar', function (Blueprint $table) {
            $table->string('cr_number', 100)->nullable()->after('dr_number');
        });
    }

    public function down(): void
    {
        Schema::table('teves_cashiers_report_lpg_ar', function (Blueprint $table) {
            $table->dropColumn('cr_number');
        });
    }
};
