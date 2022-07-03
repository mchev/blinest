<?php

namespace App\Models;

use App\Http\Traits\HasPicture;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Room extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasPicture;

    protected $appends = [
        'photo',
        'users_count',
        'current_track_index',
    ];

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
        if ($this->rounds()->exists()) {
            if ($this->rounds()->latest()->first()->current != count($this->rounds()->latest()->first()->tracks)) {
                return $this->rounds()->latest()->first()->current;
            }
        }

        return 0;
    }

    public function currentRound()
    {
        return $this->rounds()->latest()->whereNull('finished_at')->where('is_playing', true);
    }

    public function scopeIsPlaying()
    {
        return $this->currentRound()->exists();
    }

    public function startRound()
    {
        $round = $this->rounds()->create([
            // When using symphony process we loose auth and guest could also launch rounds
            'user_id' => Auth::check() ? Auth::user()->id : null,
            'tracks' => $this->tracks()->inRandomOrder()->take($this->tracks_by_round)->distinct()->pluck('id'),
        ]);
        $round->start();
    }

    public function messages()
    {
        return $this->morphMany(Message::class, 'messagable');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id')->select('id', 'name');
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

    public function scopeIsPublic($query)
    {
        $query->where('is_public', true);
    }

    public function scopeIsPrivate($query)
    {
        $query->where('is_public', false);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%');
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
