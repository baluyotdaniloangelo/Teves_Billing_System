<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teves_user_product_category_access', function (Blueprint $table) {

            $table->increments('user_product_category_access_id');

            $table->text('user_idx')
                ->comment('USER_ID from User Table');

            $table->integer('category_idx');

            $table->dateTime('created_at')->nullable();
            $table->integer('created_by_user_idx')->nullable();

            $table->dateTime('updated_at')->nullable();
            $table->integer('updated_by_user_idx')->nullable();

            $table->index(['user_idx'], 'idx_user_idx');
            $table->index(['category_idx'], 'idx_category_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teves_user_product_category_access');
    }
};