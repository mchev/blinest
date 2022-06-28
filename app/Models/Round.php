<?php

namespace App\Models;

use App\Events\RoundFinished;
use App\Events\RoundStarted;
use App\Events\TrackPaused;
use App\Events\TrackPlayed;
use App\Events\TrackResumed;
use App\Jobs\ProcessRoundFinished;
use App\Jobs\ProcessTrackPlayed;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    use HasFactory;

    protected $fillable = [
        'current',
        'finished_at',
        'is_playing',
    ];

    protected $casts = [
        'tracks' => 'object',
    ];

    protected $dates = [
        'finished_at',
    ];

    public function start()
    {
        if (! empty($this->tracks)) {
            broadcast(new RoundStarted($this));
            $this->update(['is_playing' => true]);
            $this->playNextTrack();
        }
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
        $this->update([
            'is_playing' => false,
            'finished_at' => Carbon::now(),
        ]);
        broadcast(new RoundFinished($this));
    }

    public function playNextTrack()
    {
        // The round can be stopped manually
        if ($this->finished_at) {
            return;
        }

        // All tracks has been played
        if ($this->current === count($this->tracks)) {
            $this->stop();
            if ($this->room->users_count > 0) {
                ProcessRoundFinished::dispatch($this->room)
                    ->delay(now()->addSeconds($this->room->pause_beteen_rounds));
            }

            // Else play next track
        } else {
            $this->increment('current');

            $track = Track::find($this->tracks[$this->current - 1]);
            $trackb = [
                'id' => $track->id,
                'preview_url' => $track->preview_url,
                'answers' => $track->answers->map(function ($answer) {
                    return [
                        'id' => $answer->id,
                        'name' => $answer->type->name,
                    ];
                }),
            ];

            $data = [
                'room_id' => $this->room->id,
                'round_id' => $this->id,
                'users_count' => $this->room->users_count,
                'tracks_count' => count($this->tracks),
                'current_track_index' => $this->current,
                'track' => $trackb,
            ];

            // Event
            broadcast(new TrackPlayed($data));

            // Job
            ProcessTrackPlayed::dispatch($this)
                ->delay(now()->addSeconds($this->room->track_duration));
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

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }
}
