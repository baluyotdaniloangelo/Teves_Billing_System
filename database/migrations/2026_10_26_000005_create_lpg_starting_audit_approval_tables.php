<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teves_cashiers_report_lpg_starting_audits', function (Blueprint $table) {
            $table->bigIncrements('audit_id');
            $table->unsignedBigInteger('report_id')->unique();
            $table->date('audit_date');
            $table->string('status', 20)->default('DRAFT');
            $table->unsignedBigInteger('submitted_by')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('returned_by')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->text('return_reason')->nullable();
            $table->timestamps();
            $table->index(['status', 'audit_date'], 'lpg_start_audit_status_date_idx');
        });

        Schema::create('teves_cashiers_report_lpg_starting_audit_items', function (Blueprint $table) {
            $table->bigIncrements('audit_item_id');
            $table->unsignedBigInteger('audit_id');
            $table->unsignedBigInteger('product_idx');
            $table->decimal('filled_quantity', 12, 2)->default(0);
            $table->decimal('bo_returned_customer_quantity', 12, 2)->default(0);
            $table->decimal('bo_during_delivery_quantity', 12, 2)->default(0);
            $table->timestamps();
            $table->unique(['audit_id', 'product_idx'], 'lpg_start_audit_product_unique');
            $table->foreign('audit_id', 'lpg_start_items_audit_fk')->references('audit_id')->on('teves_cashiers_report_lpg_starting_audits')->cascadeOnDelete();
            $table->index('product_idx', 'lpg_start_items_product_idx');
        });

        Schema::create('teves_lpg_audit_notifications', function (Blueprint $table) {
            $table->bigIncrements('notification_id');
            $table->unsignedBigInteger('user_idx');
            $table->unsignedBigInteger('audit_id');
            $table->string('notification_type', 30);
            $table->string('title', 180);
            $table->text('message');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->unique(['user_idx', 'audit_id', 'notification_type'], 'lpg_audit_notif_user_event_unique');
            $table->foreign('audit_id')->references('audit_id')->on('teves_cashiers_report_lpg_starting_audits')->cascadeOnDelete();
            $table->index(['user_idx', 'read_at']);
        });

        // Existing audits were already used in reports, so preserve them as approved snapshots.
        $legacy = DB::table('teves_cashiers_report_lpg_inventory as inventory')
            ->join('teves_cashiers_report as report', 'report.cashiers_report_id', '=', 'inventory.cashiers_report_id')
            ->where('inventory.inventory_mode', 'BEGINNING')
            ->whereNull('inventory.deleted_at')
            ->where('report.cashier_report_type', 'LPG')
            ->whereNull('report.deleted_at')
            ->orderBy('report.report_date')->orderBy('inventory.cashiers_report_lpg_inventory_id')
            ->get([
                'report.cashiers_report_id as report_id', 'report.report_date',
                'inventory.product_idx', 'inventory.quantity', 'inventory.bo_returned_customer_quantity',
                'inventory.bo_during_delivery_quantity', 'inventory.audit_date', 'inventory.created_at', 'inventory.updated_at',
            ]);

        foreach ($legacy->groupBy('report_id') as $reportId => $rows) {
            $first = $rows->first();
            $auditDate = $rows->pluck('audit_date')->filter()->sort()->first() ?: $first->report_date;
            $auditId = DB::table('teves_cashiers_report_lpg_starting_audits')->insertGetId([
                'report_id' => $reportId,
                'audit_date' => substr((string) $auditDate, 0, 10),
                'status' => 'APPROVED',
                'approved_at' => $first->updated_at ?: $first->created_at ?: now(),
                'created_at' => $first->created_at ?: now(),
                'updated_at' => $first->updated_at ?: $first->created_at ?: now(),
            ]);
            foreach ($rows as $row) {
                DB::table('teves_cashiers_report_lpg_starting_audit_items')->insert([
                    'audit_id' => $auditId,
                    'product_idx' => $row->product_idx,
                    'filled_quantity' => $row->quantity,
                    'bo_returned_customer_quantity' => $row->bo_returned_customer_quantity ?? 0,
                    'bo_during_delivery_quantity' => $row->bo_during_delivery_quantity ?? 0,
                    'created_at' => $row->created_at ?: now(),
                    'updated_at' => $row->updated_at ?: $row->created_at ?: now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('teves_lpg_audit_notifications');
        Schema::dropIfExists('teves_cashiers_report_lpg_starting_audit_items');
        Schema::dropIfExists('teves_cashiers_report_lpg_starting_audits');
    }
};
