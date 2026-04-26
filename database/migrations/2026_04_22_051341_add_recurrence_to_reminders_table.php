<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRecurrenceToRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reminders', function (Blueprint $table) {
			$table->boolean('is_recurring')->default(false)->after('reminder_date');

			// how often
			$table->enum('recurrence_type', ['daily','weekly','monthly'])->nullable();

			// when it ends (optional)
			$table->dateTime('recurrence_end_date')->nullable();

			// track next run
			$table->dateTime('next_run_at')->nullable();

			// optional: parent id for generated instances
			$table->unsignedBigInteger('parent_reminder_id')->nullable();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reminders', function (Blueprint $table) {
            //
        });
    }
}
