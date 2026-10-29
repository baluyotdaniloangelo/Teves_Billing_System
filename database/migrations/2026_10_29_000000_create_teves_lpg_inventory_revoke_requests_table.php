<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teves_lpg_inventory_revoke_requests', function (Blueprint $table) {
            $table->increments('request_id');
            $table->unsignedInteger('inventory_id');
            $table->unsignedInteger('report_id');
            $table->unsignedInteger('requested_by');
            $table->text('request_reason');
            $table->string('status', 16)->default('PENDING');
            $table->unsignedInteger('reviewed_by')->nullable();
            $table->dateTime('reviewed_at')->nullable();
            $table->text('decision_reason')->nullable();
            $table->timestamps();
            $table->index(['status', 'report_id'], 'idx_lpg_inventory_revoke_status_report');
            $table->index('inventory_id', 'idx_lpg_inventory_revoke_inventory');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teves_lpg_inventory_revoke_requests');
    }
};
