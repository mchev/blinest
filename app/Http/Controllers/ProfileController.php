<?php

namespace App\Http\Controllers;

use App\Models\Track;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class ProfileController extends Controller
{
    public function show(User $user): InertiaResponse
    {

        return Inertia::render('Profiles/Show', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'photo' => $user->photo,
                'team' => $user->team,
                'created_at_from_now' => $user->created_at->diffForHumans(null, true),
                'total_score' => $user->totalScores()->sum('score'),
                'total_public_score' => $user->totalScores()->whereHas('room', function ($query) {
                    $query->isPublic();
                })->sum('score'),
                'total_private_score' => $user->totalScores()->whereHas('room', function ($query) {
                    $query->isPrivate();
                })->sum('score'),
                'stats' => [
                    'rooms' => $user->moderatedRooms()->count(),
                    'playlists' => $user->moderatedPlaylists()->count(),
                    'bookmarks' => $user->bookmarkedRooms()->count(),
                ],
                'scores' => $user->totalScores()
                    ->with('room')
                    ->whereHas('room', function ($query) {
                        $query->isPublic();
                    })
                    ->select('id', 'score', 'room_id', 'updated_at')
                    ->paginate(5, ['*'], 'scores')
                    ->through(fn ($score) => [
                        'id' => $score->id,
                        'score' => $score->score,
                        'room' => $score->room,
                        'updated_at' => $score->updated_at->format('d/m/Y'),
                    ]),
                'likes' => $user->likes()->paginate(5, ['*'], 'likes'),
                'bookmarks' => $user->bookmarkedRooms()->paginate(5, ['*'], 'bookmarks'),
            ],
        ]);
    }

    public function unlikeTrack(Request $request, Track $track)
    {
        $request->user()->votes()
            ->where('votable_type', Track::class)
            ->where('votable_id', $track->id)
            ->delete();

        return back();
    }
}