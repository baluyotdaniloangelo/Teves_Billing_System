<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teves_cashiers_report_lpg_empty_inventory', function (Blueprint $table) {
            $table->unsignedInteger('advance_pull_out_quantity')->default(0)->after('swap_in_quantity');
            $table->unsignedInteger('delivery_from_advance_pull_out_quantity')->default(0)->after('borrow_quantity');
        });
    }

    public function down(): void
    {
        Schema::table('teves_cashiers_report_lpg_empty_inventory', function (Blueprint $table) {
            $table->dropColumn(['advance_pull_out_quantity', 'delivery_from_advance_pull_out_quantity']);
        });
    }
};
