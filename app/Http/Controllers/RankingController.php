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
        return response()->json([
            'week' => $room->weekScores,
            'month' => $room->monthScores,
            'lifetime' => $room->lifetimeScores,
            'teams' => $room->teamsScores,
            'user' => [
                'week' => Auth::user()->weekScoreByRoom($room)->first(),
                'month' => Auth::user()->monthScoreByRoom($room)->first(),
                'lifetime' => TotalScore::byUsers()->where('room_id', $room->id)->where('totalscorable_id', Auth::user()->id)->first(),
                'team' => Auth::user()?->team?->scoreByRoom($room)->first(),
            ],
        ], 200);
    }
}
