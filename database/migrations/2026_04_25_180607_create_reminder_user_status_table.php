<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reminder_user_status', function (Blueprint $table) {

            $table->bigIncrements('id');

            // 🔑 RELATIONS
            $table->unsignedBigInteger('reminder_id');
            $table->integer('user_id'); // ✅ MATCHES int(11)

            // 📌 STATUS
            $table->boolean('is_read')->default(false);
            $table->boolean('is_done')->default(false);

            // 🕒 TRACKING
            $table->timestamp('read_at')->nullable();
            $table->timestamp('done_at')->nullable();

            // 🧾 AUDIT
            $table->timestamps();

            // 🚀 PERFORMANCE + INTEGRITY
            $table->unique(['reminder_id', 'user_id']);

            $table->index('user_id');
            $table->index('reminder_id');

            // 🔗 FOREIGN KEYS (optional but recommended)
            $table->foreign('reminder_id')
                ->references('reminder_id')
                ->on('reminders')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('user_id')
                ->on('user_tb')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminder_user_status');
    }
};