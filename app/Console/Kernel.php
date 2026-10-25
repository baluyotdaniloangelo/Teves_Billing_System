<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
         'App\Console\Commands\DatabaseBackUp'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
	protected function schedule(Schedule $schedule)
	{
        $schedule->command('sms:birthday --send')
            ->dailyAt(config('sms.birthday_time', '08:00'))->timezone(config('sms.timezone', 'Asia/Manila'))
            ->when(function () { return (bool) config('sms.birthday_enabled'); })->withoutOverlapping(1440);

		// ✅ Daily unbilled report
		$schedule->call(function () {
			app(\App\Http\Controllers\EmailController::class)->sendUnbilledReport();
		})->dailyAt('10:00');

		// Daily rolling 30-day sales and collections report for SUAdmins.
		$schedule->call(function () {
			app(\App\Http\Controllers\EmailController::class)->sendSalesCollectionsReport();
		})->dailyAt('15:00')->timezone('Asia/Manila');

		// ✅ Reminder emails every minute
		$schedule->call(function () {
			app(\App\Http\Controllers\EmailController::class)->sendReminderEmails();
		})->everyMinute();
	}

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
