<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\CarbonInterface;

class ProfileController extends Controller
{
    public function show(User $user): \Inertia\Response
    {
        return Inertia::render('Profiles/Show', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'photo' => $user->photo,
                'team' => $user->team,
                'created_at_from_now' => $user->created_at->diffForHumans(null, true),
                'total_score' => $user->totalScores()->sum('score'),
                'total_public_score' => $user->totalScores()->whereRelation('room', function ($query) {
                    $query->isPublic();
                })->sum('score'),
                'total_private_score' => $user->totalScores()->whereRelation('room', function ($query) {
                    $query->isPrivate();
                })->sum('score'),
                'stats' => [
                    'rooms' => $user->moderatedRooms()->count(),
                    'playlists' => $user->moderatedPlaylists()->count(),
                    'bookmarks' => $user->bookmarkedRooms()->count(),
                ],
                'scores' => $user->totalScores()->with('room')->paginate(5, ['*'], 'scores')->through(fn ($score) => [
                    'id' => $score->id,
                    'score' => $score->score,
                    'room' => $score->room,
                    'updated_at' => $score->updated_at->diffForHumans(),
                ]),
                'likes' => $user->likes()->paginate(5, ['*'], 'likes'),
                'bookmarks' => $user->bookmarkedRooms()->paginate(5, ['*'], 'bookmarks'),
            ],
        ]);
    }
}
