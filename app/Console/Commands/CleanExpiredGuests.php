<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CleanExpiredGuests extends Command
{
    protected $signature = 'guests:clean';

    protected $description = 'Delete guest users older than 24 hours';

    public function handle()
    {
        $count = User::where('is_guest', true)
            ->where('created_at', '<', now()->subDay())
            ->delete();

        $this->info("Deleted {$count} expired guest users.");
    }
}
