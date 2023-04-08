<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\TotalScore;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class RankingController extends Controller
{
    public function index()
    {
        $top50Users = Cache::remember('top-50-users', now()->addMinutes(10), function () {
            return TotalScore::query()
                ->with('user')
                ->select('totalscorable_id', 'room_id')
                ->selectRaw('ROUND(SUM(score), 1) as total_score')
                ->where('totalscorable_type', 'App\Models\User')
                ->join('users', 'users.id', '=', 'total_scores.totalscorable_id')
                ->join('rooms', 'rooms.id', '=', 'total_scores.room_id')
                ->where('rooms.is_public', true)
                ->groupBy('totalscorable_id')
                ->orderByDesc('total_score')
                ->limit(50)
                ->get();
        });

        $top50Teams = Cache::remember('top-50-teams', now()->addMinutes(10), function () {
            return TotalScore::query()
                ->with('team')
                ->select('totalscorable_id', 'room_id')
                ->selectRaw('ROUND(SUM(score), 1) as total_score')
                ->where('totalscorable_type', 'App\Models\Team')
                ->join('teams', 'teams.id', '=', 'total_scores.totalscorable_id')
                ->join('rooms', 'rooms.id', '=', 'total_scores.room_id')
                ->where('rooms.is_public', true)
                ->groupBy('totalscorable_id')
                ->orderByDesc('total_score')
                ->limit(50)
                ->get();
        });

        return Inertia::render('Rankings/Index', [
            'bestUsers' => $top50Users,
            'bestTeams' => $top50Teams,
        ]);
    }

    // Room Podium
    public function roomScores(Room $room)
    {
        $scores = Cache::remember($room->slug.'-scores', now()->addMinutes(10), fn () => [
            'week' => $room->weekUsersScores,
            //'month' => $room->monthUsersScores,
            'lifetime' => $room->lifetimeUsersScores,
            'teams' => $room->lifetimeTeamsScores,
            'user' => [
                'week' => Auth::user()->weekScoreByRoom($room)->first(),
                //'month' => Auth::user()->monthScoreByRoom($room)->first(),
                'lifetime' => TotalScore::byUsers()->where('room_id', $room->id)->where('totalscorable_id', Auth::user()->id)->first(),
                'team' => Auth::user()?->team?->scoreByRoom($room)->first(),
            ],
        ]);

        return response()->json($scores);
    }
}
