<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teves_lpg_product_tracking', function (Blueprint $table) {
            $table->unsignedInteger('product_idx')->primary();
            $table->boolean('sell_out_enabled')->default(true);
            $table->boolean('empty_inventory_enabled')->default(true);
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teves_lpg_product_tracking');
    }
};
