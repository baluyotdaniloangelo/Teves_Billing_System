<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teves_cashiers_report_lpg_inventory', function (Blueprint $table) {
            $table->decimal('bo_returned_customer_quantity', 12, 2)->default(0)->after('quantity');
            $table->decimal('bo_during_delivery_quantity', 12, 2)->default(0)->after('bo_returned_customer_quantity');
            $table->date('audit_date')->nullable()->after('bo_during_delivery_quantity');
        });
    }

    public function down(): void
    {
        Schema::table('teves_cashiers_report_lpg_inventory', function (Blueprint $table) {
            $table->dropColumn(['bo_returned_customer_quantity', 'bo_during_delivery_quantity', 'audit_date']);
        });
    }
};
