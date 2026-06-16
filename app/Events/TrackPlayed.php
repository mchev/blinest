<?php

namespace App\Events;

use App\Models\Round;
use App\Models\Track;
use App\Services\TrackTimingService;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
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
            'provider' => $track->provider,
            'preview_url' => $track->preview_url,
            'audio' => $track->audio,
            'hint' => $track->hint,
            'answers' => $track->answers->map(function ($answer) {
                return [
                    'id' => $answer->id,
                    'name' => $answer->type->name,
                ];
            }),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        $roundData = $this->round->toArray();
        $timing = app(TrackTimingService::class)->timingPayload($this->round);

        return [
            'round' => array_merge($roundData, $timing),
            'room' => $this->room->toArray(),
            'track' => $this->track,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): PresenceChannel
    {
        return new PresenceChannel('rooms.'.$this->round->room_id);
    }
}
