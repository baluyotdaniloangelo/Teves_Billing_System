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
        Schema::create('teves_sms_logs_table', function (Blueprint $table) {

            $table->id('sms_log_id');

            /*
            |--------------------------------------------------------------------------
            | Campaign Information
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('campaign_id');

            /*
            |--------------------------------------------------------------------------
            | Customer Information
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('client_id')->nullable();
            $table->string('client_name',255)->nullable();
            $table->string('customer_type',100)->nullable();

            /*
            |--------------------------------------------------------------------------
            | SMS Information
            |--------------------------------------------------------------------------
            */

            $table->string('mobile_number',20);
            $table->longText('sms_message');

            /*
            |--------------------------------------------------------------------------
            | SMS Provider
            |--------------------------------------------------------------------------
            */

            $table->string('provider',50)->default('ITEXMO');
            $table->string('provider_message_id',100)->nullable();

            /*
            |--------------------------------------------------------------------------
            | SMS Result
            |--------------------------------------------------------------------------
            */

            $table->enum('sms_status',[
                'Pending',
                'Sent',
                'Delivered',
                'Failed'
            ])->default('Pending');

            $table->text('provider_response')->nullable();
            $table->text('error_message')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Dates
            |--------------------------------------------------------------------------
            */

            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Foreign Key
            |--------------------------------------------------------------------------
            */

            $table->foreign('campaign_id')
                  ->references('campaign_id')
                  ->on('teves_sms_campaign_table')
                  ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('campaign_id');
            $table->index('client_id');
            $table->index('mobile_number');
            $table->index('sms_status');
            $table->index('provider_message_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teves_sms_logs_table');
    }
};