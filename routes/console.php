<?php

use App\Jobs\CleanEmptyPlaylists;
use App\Jobs\CleanOldMessages;
use App\Jobs\CleanRooms;
use Illuminate\Support\Facades\Schedule;

// Redis Cache Tags
Schedule::command('cache:prune-stale-tags')->hourly();

// Get the top 10 users for the past 7 days and cache it
Schedule::command('topusers:weekly')->dailyAt('10:00')->emailOutputOnFailure(config('app.admin_email'));

// Clean created rounds with no scores
Schedule::command('rounds:clean')->dailyAt('04:00')->emailOutputOnFailure(config('app.admin_email'));

// Clean rounds stucked or unfinished
Schedule::command('rounds:reset')->hourly()->emailOutputOnFailure(config('app.admin_email'));

// Clean messages older than 15 days
Schedule::job(new CleanOldMessages)->everySixHours()->emailOutputOnFailure(config('app.admin_email'));

// Clean playlists without tracks
Schedule::job(new CleanEmptyPlaylists)->dailyAt('06:30')->emailOutputOnFailure(config('app.admin_email'));

// Clean rooms without playlists
Schedule::job(new CleanRooms)->dailyAt('07:00')->emailOutputOnFailure(config('app.admin_email'));
