<?php

namespace App\Models;

use App\Http\Traits\HasPicture;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use HasFactory;
    use HasPicture;
    use SoftDeletes;

    /**
     * Max members allowed by teams
     */
    public $seats = 8;

    protected $appends = [
        'photo',
        'seats',
    ];

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    public function getSeatsAttribute()
    {
        return $this->seats;
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id')->select('id', 'name', 'photo_path');
    }

    public function members()
    {
        return $this->hasMany(User::class);
    }

    public function requests()
    {
        return $this->hasMany(TeamRequest::class);
    }

    public function removeUser(User $user)
    {
        if ($user->team_id === $this->id) {
            $user->forceFill([
                'team_id' => null,
            ])->save();
        }

        $this->users()->detach($user);
    }

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
            ->selectRaw('created_at, team_id, SUM(score) as total');
    }

    public function purge()
    {
        $this->owner()->where('team_id', $this->id)
            ->update(['team_id' => null]);

        $this->members()->where('team_id', $this->id)
            ->update(['team_id' => null]);

        $this->members()->detach();

        $this->delete();
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
