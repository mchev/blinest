<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Overtrue\LaravelVote\Traits\Votable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Track extends Model
{
    use HasFactory, Votable;

    protected $appends = ['track_url'];

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

    protected function getTrackUrlAttribute()
    {
        switch ($this->provider) {
            case 'deezer':
                return 'https://www.deezer.com/track/' . $this->provider_id;
            case 'spotify':
                return 'https://open.spotify.com/track/' . $this->provider_id;
            case 'itunes':
                return 'https://music.apple.com/fr/search?term=' . implode(' ', $this->answers->map(fn($answer) => $answer->value)->toArray());
            default:
                return null;
        }
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
