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
        $bestUsers = Cache::remember('bestUsers', now()->addMinutes(10), function () {
            return TotalScore::byUsers()
                ->whereHas('user')
                ->select('totalscorable_id', 'room_id')
                ->selectRaw('SUM(score) as total_score')
                ->with('user')
                ->whereRelation('room', function ($query) {
                    $query->where('is_public', true);
                })
                ->groupBy('totalscorable_id')
                ->orderByDesc('total_score')
                ->limit(50)
                ->get();
        });

        $bestTeams = Cache::remember('bestTeams', now()->addMinutes(10), function () {
            return TotalScore::byTeams()
                ->whereHas('team')
                ->select('totalscorable_id', 'room_id')
                ->selectRaw('SUM(score) as total_score')
                ->with('team')
                ->whereRelation('room', function ($query) {
                    $query->where('is_public', true);
                })
                ->groupBy('totalscorable_id')
                ->orderByDesc('total_score')
                ->limit(50)
                ->get();
        });

        return Inertia::render('Rankings/Index', [
            'bestUsers' => $bestUsers,
            'bestTeams' => $bestTeams,
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
