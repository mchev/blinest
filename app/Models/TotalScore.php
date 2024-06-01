<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TotalScore extends Model
{
    use HasFactory;

    /**
     * Get the parent imageable model (user or post).
     */
    public function totalscorable(): MorphTo
    {
        return $this->morphTo();
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'totalscorable_id')->select('id', 'name', 'photo_path');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'totalscorable_id')->select('id', 'name', 'photo_path');
    }

    public function scopeByUsers($query)
    {
        $query->where('totalscorable_type', User::class);
    }

    public function scopeByTeams($query)
    {
        $query->where('totalscorable_type', Team::class);
    }
}
