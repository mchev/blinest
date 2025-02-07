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

    /**
     * The round instance.
     *
     * @var Round
     */
    public $round;

    /**
     * The track that ended.
     *
     * @var Track|null
     */
    public $track;

    /**
     * Create a new event instance.
     */
    public function __construct(Round $round)
    {
        $this->round = $round;
        $this->track = $this->getCurrentTrack();
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): Channel
    {
        return new Channel('rooms.'.$this->round->room_id);
    }

    /**
     * Get the current track that just ended.
     */
    private function getCurrentTrack(): ?Track
    {
        $current = ($this->round->current ?? 1) - 1;

        if ($current < 0 || ! isset($this->round->tracks[$current])) {
            return null;
        }

        return Track::with('answers')->find($this->round->tracks[$current]);
    }
}
