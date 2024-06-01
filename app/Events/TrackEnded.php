<?php

namespace App\Events;

use App\Models\Round;
use App\Models\Track;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TrackEnded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $round;

    public $track;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Round $round)
    {
        $this->round = $round;
        $this->track = Track::with('answers')->find($this->round->tracks[$this->round->current - 1]);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn(): array
    {
        return new Channel('rooms.'.$this->round->room->id);
    }
}
