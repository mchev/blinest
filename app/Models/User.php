<?php

namespace App\Models;

use App\Http\Traits\HasPicture;
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

    public function team()
    {
        return $this->hasOne(Team::class);
    }

    public function playlists()
    {
        return $this->hasMany(Playlist::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::needsRehash($password) ? Hash::make($password) : $password;
    }

    public function isAdministrator()
    {
        return $this->is_administrator;
    }

    public function isRoomModerator(Room $room = null)
    {
        return $room
            ? $room->moderators()->where('user_id', $this->id)->exists()
            : Moderator::where('user_id', $this->id)->exists();
    }

    public function isPlaylistModerator(Playlist $playlist = null)
    {
        return $playlist
            ? $playlist->moderators()->where('user_id', $this->id)->exists()
            : Moderator::where('user_id', $this->id)->exists();
    }

    public function isRoomOwner(Room $room)
    {
        return $this->id === $room->user_id;
    }

    public function isPlaylistOwner(Playlist $playlist)
    {
        return $this->id === $playlist->user_id;
    }

    public function hasRoomControl(Room $room)
    {
        return $this->isRoomOwner($room) || $this->isRoomModerator($room) || $this->isAdministrator();
    }

    public function canEditPlaylist(Playlist $playlist)
    {
        return $this->isPlaylistOwner($playlist) || $this->isPlaylistModerator($playlist) || $this->isAdministrator();
    }

    public function scopeOrderByName($query)
    {
        $query->orderBy('name');
    }

    public function scopeOwnsTeam($query)
    {
        return Team::where('user_id', $query->first()->id)->exists();
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
