<?php

namespace App\Models;

use App\Http\Traits\HasPicture;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasPicture, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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

    protected $appends = [
        'photo',
    ];

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    // TEAM

    public function team()
    {
        return $this->hasOne(Team::class);
    }

    public function scopeOwnsTeam($query)
    {
        return Team::where('user_id', $query->first()->id)->exists();
    }

    // PLAYLIST

    public function playlists()
    {
        return $this->hasMany(Playlist::class);
    }

    public function moderatedPlaylists()
    {
        return $this->morphedByMany(Playlist::class, 'moderable');
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

    // ROOM

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function moderatedRooms()
    {
        return $this->morphedByMany(Room::class, 'moderable');
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

    public function scoreByRoom(Room $room)
    {
        return $this->scores()
            ->whereRelation('round', function($query) use ($room) {
                $query->where('room_id', $room->id);
            })
            ->selectRaw("created_at, user_id, SUM(score) as total");
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
        return $this->morphMany(Message::class, 'messagable');
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

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
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
