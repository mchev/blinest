<?php

namespace App\Models;

use App\Jobs\SendDiscordNotification;
use App\Notifications\TrackDeleted;
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

    public function deleteWithNotification()
    {
        $message = 'Le titre '.$this->answers()->where('answer_type_id', 2)->first()?->value.' de '.$this->answers()->where('answer_type_id', 1)->first()?->value.' a été supprimé.';

        // Public rooms discord notification
        foreach ($this->playlist->rooms()->isPublic()->get() as $room) {
            if ($room->discord_webhook_url) {
                SendDiscordNotification::dispatch($room, $message, 'danger');
            }
        }

        // Private rooms notifications
        if (! $this->playlist->is_public) {
            foreach ($this->playlist->moderators as $moderator) {
                $moderator->notify(new TrackDeleted($this->playlist, $message));
            }
        }

        $this->answers()->delete();
        $this->delete();
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
