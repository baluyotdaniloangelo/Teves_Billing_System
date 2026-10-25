<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teves_user_presence', function (Blueprint $table) {
            // A keyed hash identifies a browser session; the session ID itself is never stored here.
            $table->char('session_key', 64)->primary();
            $table->unsignedInteger('user_idx')->index();
            $table->dateTime('last_seen_at');
            $table->dateTime('logged_out_at')->nullable();
            $table->index(['logged_out_at', 'last_seen_at'], 'user_presence_online_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teves_user_presence');
    }
};
