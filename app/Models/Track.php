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
                    // Deezer URLs now contain expiring hdnea tokens (266+ chars) - always fetch live
                    // Fallback to stored preview_url for old tracks that might still work
                    'deezer' => (new DeezerService)->getLiveTrackPreview($this->provider_id)
                        ?? ($this->preview_url && ! str_starts_with($this->preview_url, 'deezer://')
                            ? $this->preview_url
                            : null),
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

    /**
     * Payload for the in-room playlist (Answers card).
     * Omits live `audio` URLs to avoid Deezer fetches on join/history updates.
     *
     * @return array<string, mixed>
     */
    public function toPlaylistPayload(): array
    {
        $this->loadMissing('answers.type');

        return [
            'id' => $this->id,
            'provider' => $this->provider,
            'preview_url' => $this->preview_url,
            'artwork_url' => $this->artwork_url,
            'album_name' => null,
            'hint' => $this->hint,
            'upvotes' => $this->upvotes,
            'downvotes' => $this->downvotes,
            'answers' => $this->answers->map(function ($answer) {
                return [
                    'id' => $answer->id,
                    'value' => $answer->value,
                    'type' => [
                        'name' => $answer->type->name,
                    ],
                ];
            })->all(),
        ];
    }

    public function scopeFilter($query, array $filters)
    {
        // Check if we need vote counts for filtering or sorting
        $needsUpvotes = isset($filters['minUpvotes']) || (isset($filters['sortable']) && $filters['sortable']['field'] == 'votes' && $filters['sortable']['direction'] == 'asc');
        $needsDownvotes = isset($filters['minDownvotes']) || (isset($filters['sortable']) && $filters['sortable']['field'] == 'votes' && $filters['sortable']['direction'] == 'desc');

        if ($needsUpvotes) {
            $query->withTotalUpvotes();
        }
        if ($needsDownvotes) {
            $query->withTotalDownvotes();
        }

        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->whereRelation('answers', function ($query) use ($search) {
                $query->where('value', 'like', '%'.$search.'%');
            });
        })->when($filters['difficulty'] ?? null, function ($query, $difficulty) {
            $query->where('dificulty', $difficulty);
        })->when($filters['provider'] ?? null, function ($query, $provider) {
            $query->where('provider', $provider);
        })->when($filters['minUpvotes'] ?? null, function ($query, $minUpvotes) {
            $query->having('total_upvotes', '>=', $minUpvotes);
        })->when($filters['minDownvotes'] ?? null, function ($query, $minDownvotes) {
            $query->havingRaw('ABS(total_downvotes) >= ?', [$minDownvotes]);
        })->when($filters['sortable'] ?? null, function ($query, $sortable) {
            if ($sortable['field'] == 'votes') {
                if ($sortable['direction'] == 'asc') {
                    $query->orderByDesc('total_upvotes');
                } else {
                    $query->orderBy('total_downvotes');
                }
            } else {
                $query->orderBy($sortable['field'], $sortable['direction']);
            }
        });
    }
}
