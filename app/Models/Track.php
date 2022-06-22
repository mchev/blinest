<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    use HasFactory;

    public function playlist()
    {
        return $this->belongsTo(Playlist::class);
    }

    public function answers()
    {
        return $this->hasMany(TrackAnswer::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->whereRelation('answers', function ($query) use ($search) {
                $query->where('value', 'like', '%'.$search.'%');
            });
        });
    }

}
