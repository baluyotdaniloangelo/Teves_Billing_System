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
			$table->unsignedBigInteger('created_by_user_idx')->nullable()->after('reminder_date');
            $table->unsignedBigInteger('updated_by_user_idx')->nullable()->after('created_by_user_idx');
			$table->boolean('email_sent')->default(false);
			$table->boolean('is_recurring')->default(false);

			// how often
			$table->enum('recurrence_type', ['daily','weekly','monthly'])->nullable();

			// when it ends (optional)
			$table->dateTime('recurrence_end_date')->nullable();

			// track next run
			$table->dateTime('next_run_at')->nullable();

			// optional: parent id for generated instances
			$table->unsignedBigInteger('parent_reminder_id')->nullable();
			$table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};