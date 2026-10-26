<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teves_cashiers_report_lpg_starting_audit_items', function (Blueprint $table) {
            $table->text('remarks')->nullable()->after('bo_during_delivery_quantity');
        });

        DB::table('teves_cashiers_report_lpg_starting_audit_items as item')
            ->join('teves_cashiers_report_lpg_starting_audits as audit', 'audit.audit_id', '=', 'item.audit_id')
            ->join('teves_cashiers_report_lpg_inventory as inventory', function ($join) {
                $join->on('inventory.cashiers_report_id', '=', 'audit.report_id')
                    ->on('inventory.product_idx', '=', 'item.product_idx');
            })
            ->where('inventory.inventory_mode', 'BEGINNING')->whereNull('inventory.deleted_at')
            ->update(['item.remarks' => DB::raw('inventory.remarks')]);
    }

    public function down(): void
    {
        Schema::table('teves_cashiers_report_lpg_starting_audit_items', function (Blueprint $table) {
            $table->dropColumn('remarks');
        });
    }
};
