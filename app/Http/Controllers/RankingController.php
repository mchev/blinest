<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RankingController extends Controller
{
    public function index()
    {
        return Inertia::render('Rankings/Index', [
            'bestUsers' => User::select('id', 'name')
                ->whereHas('scores')
                ->withSum(['scores as score' => function($query) {
                    $query->whereRelation('round', function($query) {
                        $query->whereRelation('room', function($q) {
                            $q->where('is_public', true);
                        });
                    });
                }], 'score')
                ->orderBy('score', 'DESC')
                ->paginate(5),
            'bestTeams' => Team::select('id', 'name')
                ->whereHas('scores')
                ->withSum(['scores as score' => function($query) {
                    $query->whereRelation('round', function($query) {
                        $query->whereRelation('room', function($q) {
                            $q->where('is_public', true);
                        });
                    });
                }], 'score')
                ->orderBy('score', 'DESC')
                ->paginate(5),
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
                'lifetime' => Auth::user()->lifetimeScoreByRoom($room)->first(),
                'team' => Auth::user()?->team?->scoreByRoom($room)->first(),
            ],
        ], 200);
    }
}
