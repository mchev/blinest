<?php

namespace App\Console\Commands;

use App\Models\Score;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class WeeklyTopUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'topusers:weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache top 10 users for the last 7 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $weekly_top_users = Score::with(['user', 'round.room' => function ($query) {
            $query->where('rooms.is_public', true);
        }])
            ->where('scores.created_at', '>=', now()->subDays(7))
            ->selectRaw('scores.user_id, ROUND(SUM(scores.score), 1) as total_score')
            ->groupBy('scores.user_id')
            ->orderByDesc('total_score')
            ->limit(10)
            ->get();

        Cache::put('weekly-top-10-users', $weekly_top_users);
    }
}
