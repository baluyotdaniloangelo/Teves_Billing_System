<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('teves_sms_birthday_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedSmallInteger('birthday_year');
            $table->string('mobile_number', 12);
            $table->date('event_date');
            $table->unsignedBigInteger('campaign_id')->nullable();
            $table->timestamps();
            $table->unique(['client_id', 'birthday_year'], 'sms_birthday_client_year');
            $table->unique(['mobile_number', 'birthday_year'], 'sms_birthday_mobile_year');
            $table->index('campaign_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teves_sms_birthday_events');
    }
};
