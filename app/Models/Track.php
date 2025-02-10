<?php

namespace App\Models;

use App\Services\MusicProviders\AudiusService;
use App\Services\MusicProviders\DeezerService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Overtrue\LaravelVote\Traits\Votable;

class Track extends Model
{
    use HasFactory, Votable;

    protected $appends = [
        'track_url',
        'downvotes',
        'upvotes',
    ];

    public function playlist(): BelongsTo
    {
        return $this->belongsTo(Playlist::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(TrackAnswer::class)->orderBy('answer_type_id');
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }

    public function audio(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->provider) {
                    'deezer' => $this->created_at->year > 2023 ? (new DeezerService)->getLiveTrackPreview($this->provider_id) : $this->preview_url,
                    'spotify' => $this->preview_url,
                    'itunes' => $this->preview_url,
                    'audius' => (new AudiusService)->getLiveTrackPreview($this->provider_id),
                    default => $this->preview_url,
                };
            }
        );
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
        return formatVoteNumbers(abs($this->totalDownvotes()));
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
