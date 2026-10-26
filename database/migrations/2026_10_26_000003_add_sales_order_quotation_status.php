<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teves_sales_order_table', function (Blueprint $table) {
            $table->string('sales_order_quotation_status', 16)
                ->nullable()
                ->after('sales_order_quotation_reference');
        });
    }

    public function down(): void
    {
        Schema::table('teves_sales_order_table', function (Blueprint $table) {
            $table->dropColumn('sales_order_quotation_status');
        });
    }
};
