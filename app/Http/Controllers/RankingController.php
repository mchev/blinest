<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Support\Facades\Auth;

class RankingController extends Controller
{
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
