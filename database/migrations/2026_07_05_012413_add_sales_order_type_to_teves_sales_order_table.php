<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('teves_sales_order_table', function (Blueprint $table) {
            $table->unsignedBigInteger('category_idx')
                  ->nullable()
                  ->after('sales_order_control_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teves_sales_order_table', function (Blueprint $table) {
            $table->dropColumn('category_idx');
        });
    }
};
