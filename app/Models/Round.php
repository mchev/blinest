<?php

namespace App\Models;

use App\Events\RoundStarted;
use App\Events\RoundFinished;
use App\Events\TrackPlayed;
use App\Jobs\ProcessTrackPlayed;
use App\Jobs\ProcessRoundFinished;
use App\Models\Track;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;

use Illuminate\Support\Facades\Log;

class Round extends Model
{
    use HasFactory;

    protected $fillable = [
        'current', 
        'finished_at'
    ];

    protected $casts = [
        'tracks' => 'object',
    ];

    protected $dates = [
        'finished_at',
    ];

    protected function playing(): Attribute
    {
        return Attribute::make(
            get: fn () => Redis::get('rooms.'.$this->room->id.'.playing'),
            set: fn ($value) => Redis::set('rooms.'.$this->room->id.'.playing', $value),
        )->withoutObjectCaching();
    }

    public function start()
    {
        if (!empty($this->tracks)) {
            broadcast(new RoundStarted($this));
            $this->playing = 1;
            $this->playNextTrack();
        }
    }

    public function stop()
    { 
        $this->update(['finished_at' => Carbon::now()]);
        $this->playing = 0;
        broadcast(new RoundFinished($this));
    }

    public function playNextTrack()
    {

        if($this->current === count($this->tracks)) {
            $this->stop();
            if ($this->room->users_count > 0) {
                ProcessRoundFinished::dispatch($this->room)
                    ->delay(now()->addSeconds($this->room->pause_beteen_rounds));
            }
        }
        else {

            $this->increment('current');

            $track = Track::find($this->tracks[$this->current - 1]);
            $trackb = [
                'id' => $track->id,
                'preview_url' => $track->preview_url,
                'answers' => $track->answers->map(function($answer) {
                    return __($answer->type->name);
                })
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

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
