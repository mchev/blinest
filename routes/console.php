<?php

use App\Jobs\CleanEmptyPlaylists;
use App\Jobs\CleanOldMessages;
use App\Jobs\CleanRooms;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


// Redis Cache Tags
Schedule::command('cache:prune-stale-tags')->hourly();

// Clean created rounds with no scores
Schedule::command('rounds:clean')->dailyAt('04:00')->emailOutputOnFailure(config('app.admin_email'));

// Clean rounds stucked or unfinished
Schedule::command('rounds:reset')->everyMinute()->emailOutputOnFailure(config('app.admin_email'));

// Clean messages older than 15 days
Schedule::job(new CleanOldMessages)->dailyAt('06:00')->emailOutputOnFailure(config('app.admin_email'));

// Clean playlists without tracks
Schedule::job(new CleanEmptyPlaylists)->dailyAt('06:30')->emailOutputOnFailure(config('app.admin_email'));

// Clean rooms without playlists
Schedule::job(new CleanRooms)->dailyAt('07:00')->emailOutputOnFailure(config('app.admin_email'));
