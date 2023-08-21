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

        // Redis Cache Tags
        $schedule->command('cache:prune-stale-tags')->hourly();

        // Clean created rounds with no scores
        $schedule->command('rounds:clean')->dailyAt('04:00')->emailOutputOnFailure(config('app.admin_email'));

        // Clean rounds stucked or unfinished
        $schedule->command('rounds:reset')->everyMinute()->emailOutputOnFailure(config('app.admin_email'));

        // Clean messages older than 15 days
        $schedule->job(new CleanOldMessages)->dailyAt('06:00')->emailOutputOnFailure(config('app.admin_email'));

        // Clean playlists without tracks
        $schedule->job(new CleanEmptyPlaylists)->dailyAt('06:30')->emailOutputOnFailure(config('app.admin_email'));

        // Clean rooms without playlists
        $schedule->job(new CleanRooms)->dailyAt('07:00')->emailOutputOnFailure(config('app.admin_email'));
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
