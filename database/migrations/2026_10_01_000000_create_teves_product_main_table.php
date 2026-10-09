<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teves_product_main_table', function (Blueprint $table) {
            $table->increments('product_main_id');
            $table->string('product_name', 255)->default('');
            $table->unsignedBigInteger('category_idx')->nullable();
            $table->dateTime('created_at');
            $table->integer('created_by_user_idx')->default(0);
            $table->dateTime('updated_at');
            $table->integer('updated_by_user_idx')->default(0);
            $table->dateTime('deleted_at')->nullable();
            $table->integer('deleted_by_user_id')->default(0);
        });

        Schema::table('teves_product_table', function (Blueprint $table) {
            $table->unsignedInteger('product_main_idx')->nullable()->after('product_id');

            $table->foreign('product_main_idx', 'fk_teves_product_main_idx')
                ->references('product_main_id')
                ->on('teves_product_main_table')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::table('teves_product_table', function (Blueprint $table) {
            $table->dropForeign('fk_teves_product_main_idx');
            $table->dropColumn('product_main_idx');
        });

        Schema::dropIfExists('teves_product_main_table');
    }
};
