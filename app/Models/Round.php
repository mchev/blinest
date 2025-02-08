<?php

namespace App\Models;

use App\Events\RoundFinished;
use App\Events\TrackPaused;
use App\Events\TrackPlayed;
use App\Events\TrackResumed;
use App\Jobs\ProcessFailingTrack;
use App\Jobs\ProcessRoundFinished;
use App\Jobs\ProcessTrackPlayed;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Round extends Model
{
    use HasFactory;

    protected $fillable = [
        'current',
        'finished_at',
        'is_playing',
    ];

    protected function casts(): array
    {
        return [
            'tracks' => 'object',
            'finished_at' => 'datetime',
        ];
    }

    public function pause()
    {
        $this->update(['is_playing' => false]);
        broadcast(new TrackPaused($this->room));
    }

    public function resume()
    {
        $this->update(['is_playing' => true]);
        broadcast(new TrackResumed($this->room));
    }

    public function stop()
    {
        DB::transaction(function () {
            $this->update([
                'is_playing' => false,
                'finished_at' => now(),
            ]);

            $this->room->update(['is_playing' => false]);
        });

        broadcast(new RoundFinished($this));
    }

    public function playNextTrack()
    {
        // The round can be stopped manually
        if ($this->finished_at) {
            return;
        }

        // All tracks has been played
        if ($this->current >= count($this->tracks)) {
            $this->stop();

            ProcessRoundFinished::dispatch($this->room)
                ->delay(now()->addSeconds($this->room->pause_between_rounds));

            return;
        }

        // Get next track ID and increment counter in single query
        $current = $this->current ?? 0;
        $trackId = $this->tracks[$current];
        $this->increment('current');

        // Eager load track with audio attribute
        $track = Track::with('answers')->find($trackId);

        if (! $track) {
            Log::error('Track not found', [
                'track_id' => $trackId,
                'round_id' => $this->id,
            ]);
            $this->playNextTrack();

            return;
        }

        // Get audio URL without making additional queries
        $audioUrl = $track->audio;
        if (! $audioUrl) {
            Log::error('Missing audio URL for track', [
                'track_id' => $trackId,
                'round_id' => $this->id,
            ]);
            $this->playNextTrack();

            return;
        }

        try {
            $response = Http::timeout(5)->get($audioUrl);

            if ($response->successful()) {
                // Track is valid, broadcast and queue next track
                broadcast(new TrackPlayed($this, $track));
                ProcessTrackPlayed::dispatch($this)
                    ->delay(now()->addSeconds($this->room->track_duration));
            } else {
                // Handle HTTP error responses (404, 403, 500, etc.)
                ProcessFailingTrack::dispatch($track, "Audio URL is invalid: {$response->status()}");
                $this->playNextTrack();
            }

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // Handle connection errors (timeout, DNS failure, etc.)
            ProcessFailingTrack::dispatch($track, "Connection error: {$e->getMessage()}");
            $this->playNextTrack();
        } catch (\Exception $e) {
            // Handle other unexpected errors
            ProcessFailingTrack::dispatch($track, "Other error: {$e->getMessage()}");
            $this->playNextTrack();
        }
    }

    public function isPlaying()
    {
        return $this->is_playing;
    }

    public function userScore(User $user)
    {
        return floatval($user->scores()->where('round_id', $this->id)->sum('score'));
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }

    public function usersPodium()
    {
        return $this->scores()
            ->select([\DB::raw('SUM(score) as total'), 'user_id'])
            ->with('user')
            ->groupBy('user_id')
            ->orderByDesc('total');
    }

    public function teamsPodium()
    {
        return $this->scores()
            ->whereNotNull('team_id')
            ->select([\DB::raw('SUM(score) as total'), 'team_id'])
            ->with('team')
            ->groupBy('team_id')
            ->orderByDesc('total');
    }
}
