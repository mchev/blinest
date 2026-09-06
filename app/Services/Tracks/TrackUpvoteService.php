<?php

namespace App\Services\Tracks;

use App\Models\Track;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Support\Facades\DB;

class TrackUpvoteService
{
    public function apply(User $user, Track $track): void
    {
        DB::transaction(function () use ($user, $track): void {
            if ($user->hasUpvoted($track)) {
                $user->cancelVote($track);

                return;
            }

            if ($user->hasVoted($track)) {
                $user->cancelVote($track);
            }

            $vote = app(Vote::class);
            $vote->user_id = $user->getKey();
            $vote->votes = 1;
            $track->votes()->save($vote);
        });
    }
}
