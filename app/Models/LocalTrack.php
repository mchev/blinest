<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LocalTrack extends Model
{
    protected $fillable = [
        'artist_name',
        'track_name',
        'audio_path',
        'artwork_path',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('artist_name', 'like', '%'.$search.'%')
                ->orWhere('track_name', 'like', '%'.$search.'%');
        });
    }
}
