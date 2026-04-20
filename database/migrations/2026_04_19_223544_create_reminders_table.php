<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->bigIncrements('reminder_id'); 
            $table->string('reminders_title');
            $table->text('reminders_content')->nullable();
			$table->dateTime('reminder_date')->nullable();
			$table->boolean('is_done')->default(false);
			$table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
			$table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};