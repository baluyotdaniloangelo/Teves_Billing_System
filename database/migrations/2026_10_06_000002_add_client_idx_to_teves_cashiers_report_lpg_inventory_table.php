<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teves_cashiers_report_lpg_inventory', function (Blueprint $table) {
            $table->unsignedInteger('client_idx')->nullable()->after('product_idx');
            $table->index('client_idx', 'idx_lpg_inventory_client');
        });
    }

    public function down(): void
    {
        Schema::table('teves_cashiers_report_lpg_inventory', function (Blueprint $table) {
            $table->dropIndex('idx_lpg_inventory_client');
            $table->dropColumn('client_idx');
        });
    }
};
