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
        Schema::create('teves_bank_table', function (Blueprint $table) {
          
			$table->id('bank_id');
            $table->string('bank_name');
            $table->string('bank_account_number');
            $table->string('bank_branch')->nullable();

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
     */
    public function down(): void
    {
        Schema::dropIfExists('teves_bank_table');
    }
};