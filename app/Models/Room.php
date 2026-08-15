<?php

namespace App\Models;

use App\Http\Traits\HasPicture;
use App\Http\Traits\Sluggable;
use App\Services\RoomPresenceService;
use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Room extends Model
{
    use HasFactory;
    use HasPicture;
    use Sluggable;
    use SoftDeletes;

    protected $appends = [
        'photo',
        'current_track_index',
        'subscriptions',
    ];

    protected $hidden = ['discord_webhook_url'];

    protected $casts = [
        'is_playing' => 'boolean',
        'is_public' => 'boolean',
        'is_autostart' => 'boolean',
        'is_random' => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected $fillable = [
        'name',
        'slug',
        'description',
        'user_id',
        'category_id',
        'password',
        'is_public',
        'is_autostart',
        'is_random',
        'is_featured',
        'is_playing',
        'tracks_by_round',
        'track_duration',
        'pause_between_rounds',
        'discord_webhook_url',
        'user_count',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    protected function getCurrentTrackIndexAttribute()
    {
        if ($this->is_playing) {
            return Cache::remember('current_track_index_'.$this->id, now()->addMinutes(1), function () {
                $latestRound = $this->rounds()->latest()->first(['current', 'is_playing']);

                if ($latestRound && $latestRound->is_playing) {
                    return $latestRound->current;
                }

                return 0;
            });
        }

        return 0;
    }

    public function currentRound()
    {
        return $this->rounds()->latest()->whereNull('finished_at')->where('is_playing', true);
    }

    public function scopeIsPlaying()
    {
        return $this->is_playing;
    }

    public function subscriptions(): Attribute
    {
        return Attribute::make(
            get: function () {
                return Cache::remember('room_subscriptions_'.$this->id, now()->addSeconds(30), function () {
                    try {
                        $broadcastManager = app(BroadcastManager::class);

                        $channelInfo = $broadcastManager
                            ->getPusher()
                            ->get('/channels/'.'private-chat-room.'.$this->id, [
                                'info' => 'subscription_count',
                            ]);

                        return $channelInfo->subscription_count ?? 0;
                    } catch (\Exception $e) {
                        Log::error('Error fetching subscription count for room '.$this->id.': '.$e->getMessage());

                        return 0;
                    }
                });
            }
        );
    }

    public function bookmarks(): MorphToMany
    {
        return $this->morphToMany(User::class, 'bookmarkable', 'bookmarks')->withTimestamps();
    }

    public function messages(): MorphMany
    {
        return $this->morphMany(Message::class, 'messagable');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->select('id', 'name', 'photo_path');
    }

    public function moderators(): MorphToMany
    {
        return $this->morphToMany(User::class, 'moderable')->select('users.id', 'users.name')->withTimestamps();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function playlists(): BelongsToMany
    {
        return $this->belongsToMany(Playlist::class);
    }

    public function tracks()
    {
        return Track::whereIn('playlist_id', $this->playlists()->pluck('id'));
    }

    public function rounds(): HasMany
    {
        return $this->hasMany(Round::class);
    }

    public function scores(): HasManyThrough
    {
        return $this->hasManyThrough(Score::class, Round::class)
            ->selectRaw('scores.created_at, scores.user_id, SUM(scores.score) as total')
            ->with('user')
            ->groupBy('scores.user_id')
            ->orderBy('total', 'DESC');
    }

    public function weekUsersScores(): HasMany
    {
        return $this->hasMany(RoundStanding::class, 'room_id')
            ->selectRaw('MAX(round_standings.created_at) as max_created_at, round_standings.user_id, SUM(round_standings.total_score) as total')
            ->where('round_standings.created_at', '>=', now()->subDays(7))
            ->with('user')
            ->groupBy('round_standings.user_id')
            ->orderByDesc('total')
            ->limit(10);
    }

    public function monthUsersScores(): HasManyThrough
    {
        return $this->hasManyThrough(Score::class, Round::class)
            ->selectRaw('MAX(scores.created_at) as max_created_at, scores.user_id, SUM(scores.score) as total')
            ->where('scores.created_at', '>=', now()->subDays(30))
            ->with('user')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit(10);
    }

    public function lifetimeUsersScores(int $limit = 10): HasMany
    {
        return $this->hasMany(TotalScore::class, 'room_id')
            ->byUsers()
            ->selectRaw('SUM(score) AS total, totalscorable_id')
            ->groupBy('totalscorable_id')
            ->orderByDesc('total')
            ->with('user')
            ->limit($limit);
    }

    public function lifetimeTeamsScores(int $limit = 10): HasMany
    {
        return $this->hasMany(TotalScore::class, 'room_id')
            ->byTeams()
            ->selectRaw('SUM(score) AS total, totalscorable_id')
            ->groupBy('totalscorable_id')
            ->orderByDesc('total')
            ->with('team')
            ->limit($limit);
    }

    /**
     * @return list<int>
     */
    public static function cachedPublicIds(): array
    {
        return Cache::flexible('public_room_ids', [518400, 604800], function () {
            return static::query()->isPublic()->pluck('id')->all();
        });
    }

    /**
     * @return array<string, mixed>
     */
    public function toHomepageArray(): array
    {
        $payload = (clone $this)->setAppends(['photo'])->toArray();

        $payload['current_track_index'] = 0;
        if ($this->is_playing) {
            $currentRound = $this->currentRound()->first(['current']);
            $payload['current_track_index'] = $currentRound ? (int) ($currentRound->current ?? 0) : 0;
        }

        return $payload;
    }

    /**
     * @return array{roomId: int, memberCount: int, currentTrackIndex: int, tracksByRound: int, isPlaying: bool}
     */
    public function publicStatePayload(): array
    {
        $this->refresh();
        $currentRound = $this->currentRound()->first(['current']);

        return [
            'roomId' => $this->id,
            'memberCount' => app(RoomPresenceService::class)->getMemberCount($this),
            'currentTrackIndex' => $currentRound ? (int) ($currentRound->current ?? 0) : 0,
            'tracksByRound' => (int) $this->tracks_by_round,
            'isPlaying' => (bool) $this->is_playing,
        ];
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
