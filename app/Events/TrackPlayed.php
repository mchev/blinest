<?php

namespace App\Events;

use App\Models\Round;
use App\Models\Track;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TrackPlayed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room;

    public $round;

    public $track;

    /**
     * Create a new event instance.
     */
    public function __construct(Round $round, Track $track)
    {
        $this->round = $round;
        $this->round->load('room');
        $this->room = $round->room;
        $this->track = [
            'id' => $track->id,
            'preview_url' => $track->preview_url,
            'answers' => $track->answers->map(function ($answer) {
                return [
                    'id' => $answer->id,
                    'name' => $answer->type->name,
                ];
            }),
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): Channel
    {
        return new Channel('rooms.'.$this->round['room_id']);
    }
}
