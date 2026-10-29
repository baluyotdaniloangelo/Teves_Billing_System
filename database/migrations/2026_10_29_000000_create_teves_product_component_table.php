<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teves_product_component_table', function (Blueprint $table) {
            $table->increments('product_component_id');
            $table->integer('product_idx');
            $table->integer('component_product_idx');
            $table->decimal('quantity', 12, 3);
            $table->integer('created_by_user_idx')->nullable();
            $table->integer('updated_by_user_idx')->nullable();
            $table->timestamps();

            $table->unique(['product_idx', 'component_product_idx'], 'uq_product_component_pair');
            $table->index('component_product_idx', 'idx_product_component_item');
            $table->foreign('product_idx', 'fk_product_component_parent')
                ->references('product_id')->on('teves_product_table')->onDelete('cascade');
            $table->foreign('component_product_idx', 'fk_product_component_item')
                ->references('product_id')->on('teves_product_table')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teves_product_component_table');
    }
};
