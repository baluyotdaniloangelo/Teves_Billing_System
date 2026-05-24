<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateProductCategoryToCategoryIdxInTevesProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add new column
        Schema::table('teves_product_table', function (Blueprint $table) {
            $table->unsignedBigInteger('category_idx')
                  ->nullable()
                  ->after('product_category');
        });

        // Copy existing data
        DB::statement('
            UPDATE teves_product_table
            SET category_idx = product_category
        ');

        // Remove old column
        Schema::table('teves_product_table', function (Blueprint $table) {
            $table->dropColumn('product_category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Restore old column
        Schema::table('teves_product_table', function (Blueprint $table) {
            $table->string('product_category')->nullable();
        });

        // Restore data
        DB::statement('
            UPDATE teves_product_table
            SET product_category = category_idx
        ');

        // Remove new column
        Schema::table('teves_product_table', function (Blueprint $table) {
            $table->dropColumn('category_idx');
        });
    }
}