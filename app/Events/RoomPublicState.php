<?php

namespace App\Events;

use App\Models\Room;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Public (no auth) event for home page room vignettes: count and progress.
 * Source: Redis member count + room/round state.
 */
class RoomPublicState implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Room $room
    ) {}

    public function broadcastOn(): Channel
    {
        return new Channel('room.public.'.$this->room->id);
    }

    public function broadcastAs(): string
    {
        return 'RoomPublicState';
    }

    public function broadcastWith(): array
    {
        return $this->room->publicStatePayload();
    }
}
