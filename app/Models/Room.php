<?php

namespace App\Models;

use App\Http\Traits\HasPicture;
use App\Http\Traits\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Room extends Model
{
    use HasFactory;
    use HasPicture;
    use Sluggable;
    use SoftDeletes;

    protected $appends = [
        'photo',
        'users_count',
        'current_track_index',
    ];

    protected $hidden = ['discord_webhook_url'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    protected function getUsersCountAttribute()
    {
        $response = pusher()->get('/channels/presence-rooms.'.$this->id.'/users');

        return count($response->users);
    }

    protected function getCurrentTrackIndexAttribute()
    {
        return Cache::remember('current_track_index_'.$this->id, now()->addMinutes(1), function () {
            $latestRound = $this->rounds()->latest()->first(['current', 'is_playing']);

            if ($latestRound && $latestRound->is_playing) {
                return $latestRound->current;
            }

            return 0;
        });
    }

    public function currentRound()
    {
        return $this->rounds()->latest()->whereNull('finished_at')->where('is_playing', true);
    }

    public function scopeIsPlaying()
    {
        return $this->currentRound()->exists();
    }

    public function bookmarks(): MorphToMany
    {
        return $this->morphToMany(User::class, 'bookmarkable', 'bookmarks')->withTimestamps();
    }

    public function messages()
    {
        return $this->morphMany(Message::class, 'messagable');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id')->select('id', 'name', 'photo_path');
    }

    public function moderators()
    {
        return $this->morphToMany(User::class, 'moderable')->select('users.id', 'users.name')->withTimestamps();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function playlists()
    {
        return $this->belongsToMany(Playlist::class);
    }

    public function tracks()
    {
        return Track::whereIn('playlist_id', $this->playlists()->pluck('id'));
    }

    public function rounds()
    {
        return $this->hasMany(Round::class);
    }

    public function scores()
    {
        return $this->hasManyThrough(Score::class, Round::class)
            ->selectRaw('scores.created_at, scores.user_id, SUM(scores.score) as total')
            ->with('user')
            ->groupBy('scores.user_id')
            ->orderBy('total', 'DESC');
    }

    public function weekUsersScores()
    {
        return $this->hasManyThrough(Score::class, Round::class)
            ->selectRaw('MAX(scores.created_at) as max_created_at, scores.user_id, SUM(scores.score) as total')
            ->where('scores.created_at', '>=', now()->subDays(7))
            ->with('user')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit(10);
    }

    public function monthUsersScores()
    {
        return $this->hasManyThrough(Score::class, Round::class)
            ->selectRaw('MAX(scores.created_at) as max_created_at, scores.user_id, SUM(scores.score) as total')
            ->where('scores.created_at', '>=', now()->subDays(30))
            ->with('user')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit(10);
    }

    public function lifetimeUsersScores(int $limit = 10)
    {
        return $this->hasMany(TotalScore::class, 'room_id')
            ->byUsers()
            ->selectRaw('SUM(score) AS total, totalscorable_id')
            ->groupBy('totalscorable_id')
            ->orderByDesc('total')
            ->with('user')
            ->limit($limit);
    }

    public function lifetimeTeamsScores(int $limit = 10)
    {
        return $this->hasMany(TotalScore::class, 'room_id')
            ->byTeams()
            ->selectRaw('SUM(score) AS total, totalscorable_id')
            ->groupBy('totalscorable_id')
            ->orderByDesc('total')
            ->with('team')
            ->limit($limit);
    }

    public function scopeIsPublic($query)
    {
        $query->where('is_public', true);
    }

    public function scopeIsAutostart($query)
    {
        $query->where('is_autostart', true);
    }

    public function scopeIsPrivate($query)
    {
        $query->where('is_public', false);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhereHas('owner', function ($query) use ($search) {
                        $query->where('name', 'like', '%'.$search.'%');
                    });
            });
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }
}
