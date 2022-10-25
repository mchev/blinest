<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Overtrue\LaravelVote\Traits\Votable;

class Track extends Model
{
    use HasFactory, Votable;

    protected $appends = [
        'track_url',
        'downvotes',
        'upvotes',
    ];

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

    public function getTrackUrlAttribute()
    {
        if ($this->provider_url) {
            return $this->provider_url;
        } else {
            switch ($this->provider) {
                case 'deezer':
                    return 'https://www.deezer.com/track/'.$this->provider_id;
                case 'spotify':
                    return 'https://open.spotify.com/track/'.$this->provider_id;
                case 'itunes':
                    return 'https://music.apple.com/fr/search?term='.implode(' ', $this->answers->map(fn ($answer) => $answer->value)->toArray());
                default:
                    return null;
            }
        }
    }

    public function getUpvotesAttribute()
    {
        return formatVoteNumbers($this->totalUpvotes());
    }

    public function getDownvotesAttribute()
    {
        return formatVoteNumbers($this->totalDownvotes());
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->whereRelation('answers', function ($query) use ($search) {
                $query->where('value', 'like', '%'.$search.'%');
            });
        })->when($filters['sortable'] ?? null, function ($query, $sortable) {
            if ($sortable['field'] == 'votes') {
                if ($sortable['direction'] == 'asc') {
                    $query->withTotalUpvotes()->orderByDesc('total_upvotes');
                } else {
                    $query->withTotalDownvotes()->orderBy('total_downvotes');
                }
            } else {
                $query->orderBy($sortable['field'], $sortable['direction']);
            }
        });
    }
}
