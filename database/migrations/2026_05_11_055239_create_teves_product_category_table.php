<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTevesProductCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teves_product_category', function (Blueprint $table) {

            $table->id('category_id');
            $table->string('category_name')->nullable();

            // Audit fields
            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by_user_idx')->nullable();

            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by_user_idx')->nullable();

            $table->softDeletes(); // deleted_at
            $table->unsignedBigInteger('deleted_by_user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teves_product_category');
    }
}