<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Overtrue\LaravelVote\Traits\Votable;

class Track extends Model
{
    use HasFactory, Votable;

    public function playlist()
    {
        return $this->belongsTo(Playlist::class);
    }

    public function answers()
    {
        return $this->hasMany(TrackAnswer::class);
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->whereRelation('answers', function ($query) use ($search) {
                $query->where('value', 'like', '%'.$search.'%');
            });
        });
    }
}
