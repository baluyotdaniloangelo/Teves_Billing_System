<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teves_product_table', function (Blueprint $table) {
            $table->string('product_photo_path')->nullable()->after('product_code');
        });
    }

    public function down(): void
    {
        Schema::table('teves_product_table', function (Blueprint $table) {
            $table->dropColumn('product_photo_path');
        });
    }
};
