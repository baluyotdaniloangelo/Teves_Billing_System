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
        Schema::create('teves_sales_agent_commission', function (Blueprint $table) {

            $table->id('commission_id');

            $table->unsignedBigInteger('sales_agent_idx');
            $table->unsignedBigInteger('category_idx');

            $table->enum('commission_type', [
                'percentage',
                'fixed_per_unit',
                'fixed_amount'
            ])->default('percentage');

            $table->decimal('commission_value', 15, 2)
                  ->default(0);

            $table->timestamps();
            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index(
                'sales_agent_idx',
                'idx_sales_agent_idx'
            );

            $table->index(
                'category_idx',
                'idx_category_idx'
            );

            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Commission Setup
            |--------------------------------------------------------------------------
            */

            $table->unique(
                [
                    'sales_agent_idx',
                    'category_idx'
                ],
                'unq_agent_category'
            );

            /*
            |--------------------------------------------------------------------------
            | Foreign Keys
            |--------------------------------------------------------------------------
            */
/*
            $table->foreign(
                    'sales_agent_idx',
                    'fk_commission_sales_agent'
                )
                ->references('sales_agent_idx')
                ->on('teves_sales_agent');

            $table->foreign(
                    'category_idx',
                    'fk_commission_category'
                )
                ->references('category_idx')
                ->on('teves_product_category');*/
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teves_sales_agent_commission');
    }
};