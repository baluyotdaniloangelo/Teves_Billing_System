<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reminders', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_user_idx')->nullable()->after('reminder_date');
            $table->unsignedBigInteger('updated_by_user_idx')->nullable()->after('created_by_user_idx');
        });
    }

    public function down(): void
    {
        Schema::table('reminders', function (Blueprint $table) {
            $table->dropColumn(['created_by_user_idx', 'updated_by_user_idx']);
        });
    }
};