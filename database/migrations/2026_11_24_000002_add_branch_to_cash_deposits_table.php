<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teves_cash_deposits', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_idx')->nullable()->after('bank_idx');
            $table->index(['branch_idx', 'cash_deposit_date'], 'cash_deposits_branch_date_idx');
        });

        if (!Schema::hasTable('teves_cashiers_report_p9') || !Schema::hasTable('teves_cashiers_report')) {
            return;
        }

        // Historical deposits retain their original cashier report ID after the standalone migration.
        DB::table('teves_cashiers_report_p9 as legacy')
            ->join('teves_cashiers_report as report', 'report.cashiers_report_id', '=', 'legacy.cashiers_report_idx')
            ->whereNotNull('report.teves_branch')
            ->select('legacy.cashiers_report_p9_id', 'report.teves_branch')
            ->orderBy('legacy.cashiers_report_p9_id')
            ->chunk(500, function ($rows) {
                foreach ($rows as $row) {
                    DB::table('teves_cash_deposits')
                        ->where('cash_deposit_id', $row->cashiers_report_p9_id)
                        ->whereNull('branch_idx')
                        ->update(['branch_idx' => $row->teves_branch]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('teves_cash_deposits', function (Blueprint $table) {
            $table->dropIndex('cash_deposits_branch_date_idx');
            $table->dropColumn('branch_idx');
        });
    }
};
