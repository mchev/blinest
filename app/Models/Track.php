<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jcc\LaravelVote\Traits\Votable;

class Track extends Model
{
    use HasFactory;
    use Votable;

    public function playlist()
    {
        return $this->belongsTo(Playlist::class);
    }

    public function answers()
    {
        return $this->hasMany(TrackAnswer::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('first_name', 'like', '%'.$search.'%')
                    ->orWhere('last_name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        });
    }

}
