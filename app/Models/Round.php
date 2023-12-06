<?php

namespace App\Models;

use App\Events\RoundFinished;
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
        'finished_at' => 'datetime',
    ];

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
        $this->room()->update(['is_playing' => false]);
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
            if ($this->room->users_count > 0) {
                ProcessRoundFinished::dispatch($this->room)
                    ->delay(now()->addSeconds($this->room->pause_between_rounds));
            }

            // Else play next track
        } else {
            $this->increment('current');
            $track = Track::find($this->tracks[$this->current - 1]);

            if (@file_get_contents($track->preview_url)) {

                // Event
                broadcast(new TrackPlayed($this, $track));

                // Job
                ProcessTrackPlayed::dispatch($this)
                    ->delay(now()->addSeconds($this->room->track_duration));
            } else {
                $track->deleteWithNotification();
                $this->playNextTrack();
            }
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
