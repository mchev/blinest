<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UpdateScore implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var User
     */
    public $update;


    /**
     * Create a new event instance.
     *
     * @param Message $message
     */
    public function __construct($update)
    {
        $this->update = $update;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('newUserScore-' . $this->update['game_id']);
    }
}
