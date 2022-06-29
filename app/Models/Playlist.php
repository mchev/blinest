<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Playlist extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function moderators()
    {
        return $this->morphToMany(User::class, 'moderable')->withTimestamps();
    }

    public function scopeIsPublic($query)
    {
        $query->where('is_public', true);
    }

    public function tracks()
    {
        return $this->hasMany(Track::class);
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class);
    }

    public function hasProviderTrack(string $providerId)
    {
        return $this->tracks()->where('provider_id', $providerId);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%');
            })->orWhereRelation('owner', function ($query) use ($search) {
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
