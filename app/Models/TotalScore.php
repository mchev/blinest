<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TotalScore extends Model
{
    use HasFactory;

    /**
     * Get the parent imageable model (user or post).
     */
    public function totalscorable()
    {
        return $this->morphTo();
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'totalscorable_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'totalscorable_id');
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
