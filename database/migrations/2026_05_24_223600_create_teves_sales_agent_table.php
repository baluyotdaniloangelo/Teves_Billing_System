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
        Schema::create('teves_sales_agent_table', function (Blueprint $table) {

            $table->id('sales_agent_id');

            $table->string('sales_agent_name');
            $table->string('sales_agent_contact_number')->nullable();
            $table->string('sales_agent_email_address')->nullable();
            $table->text('sales_agent_address')->nullable();

            $table->enum('sales_agent_status', [
                'active',
                'inactive'
            ])->default('active');
			$table->unsignedBigInteger('created_by_user_idx')->nullable();
            $table->unsignedBigInteger('updated_by_user_idx')->nullable();
            $table->unsignedBigInteger('deleted_by_user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teves_sales_agent_table');
    }
};