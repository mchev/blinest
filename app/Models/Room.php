<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Redis;

class Room extends Model
{
    
    use HasFactory;
    use SoftDeletes;

    protected $appends = [
        'counter',
    ];

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    public function getCounterAttribute()
    {
        return Redis::exists('presence-rooms.'.$this->id) ? count(Redis::get('presence-rooms.'.$this->id)) : 0;
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function playlists()
    {
        return $this->belongsToMany(Playlist::class);
    }

    public function moderators()
    {
        return $this->hasMany(Moderator::class);
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
