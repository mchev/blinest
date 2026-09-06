<?php

namespace App\Services\Tracks;

use App\Models\Track;
use App\Models\User;

class TrackVotePayloadService
{
    /**
     * @return array{upvotes: int|string, downvotes: int|string, user_voted_up: bool, user_voted_down: bool}
     */
    public function forTrack(Track $track, ?User $user): array
    {
        $track->refresh();

        return [
            'upvotes' => $track->upvotes,
            'downvotes' => $track->downvotes,
            'user_voted_up' => $user?->hasUpvoted($track) ?? false,
            'user_voted_down' => $user?->hasDownvoted($track) ?? false,
        ];
    }
}
