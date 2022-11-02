<?php

namespace App\Http\Controllers;

use App\Models\Score;
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
            'bestUsers' => Score::whereRelation('round', function($query) {
                        $query->whereRelation('room', function($q) {
                            $q->where('is_public', true);
                        });
                    })
                    ->selectRaw("SUM(score) as total, user_id")
                    ->with('user')
                    ->groupBy('user_id')
                    ->orderBy('total', 'DESC')
                    ->paginate(5),
            'bestTeams' => Score::whereNotNull('team_id')
                    ->whereRelation('round', function($query) {
                        $query->whereRelation('room', function($q) {
                            $q->where('is_public', true);
                        });
                    })
                    ->selectRaw("SUM(score) as total, team_id")
                    ->with('team')
                    ->groupBy('team_id')
                    ->orderBy('total', 'DESC')
                    ->paginate(5)

        ]);

        dd($scores);

        // return Inertia::render('Rankings/Index', [
        //     'bestUsers' => User::select('id', 'name')
        //         ->whereHas('scores')
        //         ->withSum(['scores as score' => function($query) {
        //             $query->whereRelation('round', function($query) {
        //                 $query->whereRelation('room', function($q) {
        //                     $q->where('is_public', true);
        //                 });
        //             });
        //         }], 'score')
        //         ->orderBy('score', 'DESC')
        //         ->paginate(5),
        //     'bestTeams' => Team::select('id', 'name')
        //         ->whereHas('scores')
        //         ->withSum(['scores as score' => function($query) {
        //             $query->whereRelation('round', function($query) {
        //                 $query->whereRelation('room', function($q) {
        //                     $q->where('is_public', true);
        //                 });
        //             });
        //         }], 'score')
        //         ->orderBy('score', 'DESC')
        //         ->paginate(5),
        // ]);
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
