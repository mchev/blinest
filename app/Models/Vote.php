<?php

namespace App\Models;

use App\Enums\TrackDownvoteReason;
use Overtrue\LaravelVote\Vote as BaseVote;

class Vote extends BaseVote
{
    protected $casts = [
        'votes' => 'int',
        'downvote_reason' => TrackDownvoteReason::class,
    ];
}
