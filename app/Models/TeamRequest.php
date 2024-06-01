<?php

namespace App\Models;

use App\Notifications\TeamRequestApproved;
use App\Notifications\TeamRequestRejected;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamRequest extends Model
{
    protected $casts = [
        'declined_at' => 'datetime',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approve()
    {
        $this->user()->update([
            'team_id' => $this->team_id,
        ]);
        $this->user->notify(new TeamRequestApproved($this));
        $this->user->teamRequests()->delete();
    }

    public function reject()
    {
        $this->user->notify(new TeamRequestRejected($this));
        $this->update([
            'declined_at' => now(),
        ]);
    }

    public function cancel()
    {
        return $this->delete();
    }
}
