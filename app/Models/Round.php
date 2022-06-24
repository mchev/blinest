<?php

namespace App\Models;

use App\Events\RoundStarted;
use App\Events\RoundFinished;
use App\Events\TrackPlayed;

use App\Models\Track;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;

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
            get: fn () => Redis::set('rooms.'.$this->room->id.'.playing'),
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
        //$this->finished_at = Carbon::now();
        $this->playing = 0;
        broadcast(new RoundFinished($this));
    }

    public function playNextTrack()
    {

        if($this->current === count($this->tracks)) {
            $this->stop();
            // $this->room()->startRound();
            // $this->room->startRound();
            if ($this->room->users_count > 0) {
                //run_background_process('round:countdown', $this->room->id);
            }
        }
        else {

            $this->increment('current');

            $data = [
                'room_id' => $this->room->id,
                'round_id' => $this->id,
                'users_count' => $this->room->users_count,
                'tracks_count' => count($this->tracks),
                'current_track_index' => $this->current,
                'track' => Track::select('id', 'preview_url')->find($this->tracks[$this->current - 1]),
            ];

            broadcast(new TrackPlayed($data));
            run_background_process('track:countdown', $this->id);

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
