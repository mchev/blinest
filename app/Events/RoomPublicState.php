<?php

namespace App\Events;

use App\Models\Room;
use App\Services\RoomPresenceService;
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
        $this->room->refresh();
        $memberCount = app(RoomPresenceService::class)->getMemberCount($this->room);
        $currentRound = $this->room->currentRound()->first();
        $currentTrackIndex = $currentRound ? ($currentRound->current ?? 0) : 0;
        $tracksByRound = (int) $this->room->tracks_by_round;

        return [
            'roomId' => $this->room->id,
            'memberCount' => $memberCount,
            'currentTrackIndex' => $currentTrackIndex,
            'tracksByRound' => $tracksByRound,
            'isPlaying' => (bool) $this->room->is_playing,
        ];
    }
}
