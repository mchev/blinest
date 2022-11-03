<?php

namespace App\Console;

use App\Jobs\CleanEmptyPlaylists;
use App\Jobs\CleanOldMessages;
use App\Jobs\CleanRooms;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new CleanOldMessages)->dailyAt('06:00')->emailOutputOnFailure(env('ADMIN_EMAIL'));
        $schedule->job(new CleanEmptyPlaylists)->dailyAt('06:30')->emailOutputOnFailure(env('ADMIN_EMAIL'));
        $schedule->job(new CleanRooms)->dailyAt('07:00')->emailOutputOnFailure(env('ADMIN_EMAIL'));
        $schedule->command('sitemap:generate')->daily()->emailOutputOnFailure(env('ADMIN_EMAIL'));
        $schedule->command('ban:delete-expired')->everyMinute();
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
