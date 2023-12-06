<?php

namespace App\Models;

use App\Http\Traits\HasPicture;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Mchev\Banhammer\Traits\Bannable;
use Overtrue\LaravelVote\Traits\Voter;

class User extends Authenticatable
{
    use Bannable, HasApiTokens, HasFactory, HasPicture, Notifiable, SoftDeletes, Voter;

    protected $appends = [
        'photo',
    ];

    //protected $with = [ 'photo_path' ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'email',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    // Likes
    public function likes()
    {
        return app(Track::class)->whereHas(
            'voters',
            function ($q) {
                return $q->where(config('vote.user_foreign_key'), $this->getKey())->where('votes', 1);
            }
        )->with('answers');
    }

    // TEAM

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id')
            ->whereRelation('owner', function ($query) {
                $query->notBanned();
            });
    }

    public function hasTeam()
    {
        return $this->team()->exists();
    }

    public function scopeOwnsTeam($query)
    {
        return Team::where('user_id', $query->first()->id)->exists();
    }

    public function teamRequests()
    {
        return $this->hasMany(TeamRequest::class);
    }

    // PLAYLIST

    public function playlists()
    {
        return $this->hasMany(Playlist::class);
    }

    public function moderatedPlaylists()
    {
        return $this->morphedByMany(Playlist::class, 'moderable')->orderBy('name');
    }

    public function isPlaylistOwner(Playlist $playlist)
    {
        return $this->id === $playlist->user_id;
    }

    public function isPlaylistModerator(Playlist $playlist)
    {
        return $this->whereHas('moderatedPlaylists', function (Builder $query) use ($playlist) {
            $query->where('playlists.id', $playlist->id);
        })->exists();
    }

    public function canEditPlaylist(Playlist $playlist)
    {
        return $this->isPlaylistOwner($playlist) || $this->isPlaylistModerator($playlist) || $this->isAdministrator();
    }

    // Bookmarks

    public function bookmarkedRooms(): MorphToMany
    {
        return $this->morphedByMany(Room::class, 'bookmarkable', 'bookmarks')->withTimestamps();
    }

    // ROOM

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function moderatedRooms()
    {
        return $this->morphedByMany(Room::class, 'moderable')->orderBy('name');
    }

    public function isRoomModerator(Room $room)
    {
        return $this->whereHas('moderatedRooms', function (Builder $query) use ($room) {
            $query->where('rooms.id', $room->id);
        })->exists();
    }

    public function isRoomOwner(Room $room)
    {
        return $this->id === $room->user_id;
    }

    public function hasRoomControl(Room $room)
    {
        return $this->isRoomOwner($room) || $this->isRoomModerator($room) || $this->isAdministrator();
    }

    // SCORES

    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    public function totalScores()
    {
        return $this->morphMany(TotalScore::class, 'totalscorable');
    }

    public function scoreByRoom(Room $room)
    {
        return $this->scores()
            ->whereRelation('round', function ($query) use ($room) {
                $query->where('room_id', $room->id);
            })
            ->selectRaw('created_at, user_id, SUM(score) as total');
    }

    public function maxScoreByRoom(Room $room)
    {
        return $this->scores()
            ->whereRelation('round', function ($query) use ($room) {
                $query->where('room_id', $room->id)->where('round_id', '!=', $room->id);
            })
            ->groupBy('round_id')
            ->selectRaw('SUM(score) as total')
            ->orderByDesc('total')
            ->limit(1);
    }

    public function weekScoreByRoom(Room $room)
    {
        return $this->scoreByRoom($room)
            ->where('created_at', '>=', now()->subDays(7));
    }

    public function monthScoreByRoom(Room $room)
    {
        return $this->scoreByRoom($room)
            ->where('created_at', '>=', now()->subDays(30));
    }

    public function lifetimeScoreByRoom(Room $room)
    {
        return $this->scoreByRoom($room);
    }

    // CHAT
    public function messages()
    {
        return $this->hasMany(Message::class, 'user_id');
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::needsRehash($password) ? Hash::make($password) : $password;
    }

    public function isAdministrator()
    {
        return $this->is_administrator;
    }

    public function scopeOrderByName($query)
    {
        $query->orderBy('name');
    }

    public function scopePublicModerators($query)
    {
        $query->whereRelation('moderatedRooms', fn ($room) => $room->where('is_public', true));
    }

    public function isPublicModerator()
    {
        return $this->moderatedRooms()->where('is_public', true)->exists();
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) use ($filters) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            })
                ->orderByRaw('CASE WHEN name = "'.$filters['search'].'" THEN 1 WHEN name LIKE "%'.$filters['search'].'%" THEN 2 ELSE 3 END');
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }

    public function getUpVotedItems(string $model)
    {
        return app($model)->whereHas(
            'voters',
            function ($q) {
                return $q->where(config('vote.user_foreign_key'), $this->getKey())->where('votes', 1);
            }
        );
    }
}
