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

    public static function boot()
    {
        parent::boot();

        self::creating(function($model) {
            $model->user_id = Auth::user()->id;
            $model->tracks = $model->room->tracks()->inRandomOrder()->take($model->room->tracks_by_game)->pluck('id');
        });
    }

    protected function playing(): Attribute
    {
        return Attribute::make(
            get: fn () => Redis::set('rooms.'.$this->room->id.'.playing'),
            set: fn ($value) => Redis::set('rooms.'.$this->room->id.'.playing', $value),
        )->withoutObjectCaching();
    }

    public function start()
    {
        broadcast(new RoundStarted($this));
        $this->playing = true;
        $this->playNextTrack();
    }

    public function stop()
    { 
        //$this->finished_at = Carbon::now();
        $this->playing = false;
        broadcast(new RoundFinished($this));
    }

    public function playNextTrack()
    {

        if($this->current === count($this->tracks))
            $this->stop();
            // if ($this->room->counter > 0) 
            //     $this->start();

        else if(!empty($this->tracks)) {

            $this->increment('current');

            $data = [
                'room_id' => $this->room->id,
                'round_id' => $this->id,
                'users_count' => $this->room->counter,
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
