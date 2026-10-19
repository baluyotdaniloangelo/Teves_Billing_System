<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teves_product_table', function (Blueprint $table) {
            $table->decimal('product_capacity', 10, 3)->nullable();
            $table->string('product_capacity_unit', 10)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('teves_product_table', function (Blueprint $table) {
            $table->dropColumn(['product_capacity', 'product_capacity_unit']);
        });
    }
};
