<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class LocalTrack extends Model
{
    protected $fillable = [
        'artist_name',
        'track_name',
        'audio_path',
        'artwork_path',
        'user_id',
    ];

    protected $appends = ['audio_url', 'artwork_url'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tracks(): HasMany
    {
        return $this->hasMany(Track::class, 'provider_id')
            ->where('provider', 'local');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('artist_name', 'like', '%'.$search.'%')
                ->orWhere('track_name', 'like', '%'.$search.'%');
        });
    }

    public function getAudioUrlAttribute()
    {
        return $this->audio_path ? Storage::url($this->audio_path) : null;
    }

    public function getArtworkUrlAttribute()
    {
        return $this->artwork_path ? Storage::url($this->artwork_path) : null;
    }
}
