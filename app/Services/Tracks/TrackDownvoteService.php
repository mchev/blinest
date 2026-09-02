<?php

namespace App\Services\Tracks;

use App\Enums\TrackDownvoteReason;
use App\Models\Track;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TrackDownvoteService
{
    public function apply(User $user, Track $track, ?TrackDownvoteReason $reason): void
    {
        DB::transaction(function () use ($user, $track, $reason): void {
            if ($user->hasDownvoted($track)) {
                $user->cancelVote($track);

                return;
            }

            if ($reason === null) {
                throw ValidationException::withMessages([
                    'reason' => __('A downvote reason is required.'),
                ]);
            }

            if ($user->hasVoted($track)) {
                $user->cancelVote($track);
            }

            $vote = app(Vote::class);
            $vote->user_id = $user->getKey();
            $vote->votes = -1;
            $vote->downvote_reason = $reason;
            $track->votes()->save($vote);
        });
    }
}
