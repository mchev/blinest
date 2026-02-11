<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoomState implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @param  array{users: array<int, array>, scores: array<int, float>, roundId: int|null}  $state
     */
    public function __construct(
        public int $roomId,
        public array $state
    ) {}

    public function broadcastOn(): PresenceChannel
    {
        return new PresenceChannel('rooms.'.$this->roomId);
    }

    public function broadcastAs(): string
    {
        return 'RoomState';
    }

    public function broadcastWith(): array
    {
        return [
            'users' => $this->state['users'],
            'scores' => $this->state['scores'],
            'roundId' => $this->state['roundId'],
        ];
    }
}
