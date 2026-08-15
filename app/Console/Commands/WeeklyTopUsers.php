<?php

namespace App\Console\Commands;

use App\Models\RoundStanding;
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
    public function handle(): void
    {
        // Utiliser round_standings au lieu de scores (plus performant et cohérent)
        $weeklyTopUsers = RoundStanding::query()
            ->with('user:id,name,photo_path,elo')
            ->join('rounds', 'round_standings.round_id', '=', 'rounds.id')
            ->join('rooms', 'round_standings.room_id', '=', 'rooms.id')
            ->where('rooms.is_public', true)
            ->where('round_standings.created_at', '>=', now()->subDays(7))
            ->selectRaw('round_standings.user_id, ROUND(SUM(round_standings.total_score), 1) as total_score')
            ->groupBy('round_standings.user_id')
            ->orderByDesc('total_score')
            ->limit(10)
            ->get()
            ->map(fn (RoundStanding $standing) => [
                'user_id' => $standing->user_id,
                'total_score' => $standing->total_score,
                'user' => $standing->user ? [
                    'id' => $standing->user->id,
                    'name' => $standing->user->name,
                    'photo' => $standing->user->photo,
                    'elo' => $standing->user->elo,
                ] : null,
            ])
            ->values()
            ->all();

        Cache::put('weekly-top-10-users', $weeklyTopUsers);
        $this->info('Weekly top 10 users cached successfully');
    }
}
