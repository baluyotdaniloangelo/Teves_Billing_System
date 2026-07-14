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
        Schema::create('teves_sms_campaign_table', function (Blueprint $table) {

            $table->id('campaign_id');

            // Campaign Information
            $table->string('campaign_title', 255)->nullable();
            $table->string('customer_type', 100);
            $table->longText('sms_message');

            // Statistics
            $table->unsignedInteger('total_recipients')->default(0);
            $table->unsignedInteger('total_sent')->default(0);
            $table->unsignedInteger('total_failed')->default(0);

            // Provider
            $table->string('provider', 50)->default('ITEXMO');

            // Status
            $table->enum('campaign_status', [
                'Pending',
                'Processing',
                'Completed',
                'Completed With Errors',
                'Cancelled'
            ])->default('Pending');

            // Audit Trail
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('customer_type');
            $table->index('campaign_status');
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teves_sms_campaign_table');
    }
};