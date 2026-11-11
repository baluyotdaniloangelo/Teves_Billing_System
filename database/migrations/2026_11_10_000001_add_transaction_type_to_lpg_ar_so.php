<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teves_cashiers_report_lpg_ar_so', function (Blueprint $table) {
            $table->string('transaction_type', 20)->nullable()->after('item_description');
        });

        DB::table('teves_cashiers_report_lpg_ar_so')->where('item_description', 'Sales Order')->update(['transaction_type' => 'A.R.']);
        DB::table('teves_cashiers_report_lpg_ar_so')->where('item_description', 'Cash Sales')->update(['transaction_type' => 'Cash']);
    }

    public function down(): void
    {
        Schema::table('teves_cashiers_report_lpg_ar_so', function (Blueprint $table) {
            $table->dropColumn('transaction_type');
        });
    }
};
