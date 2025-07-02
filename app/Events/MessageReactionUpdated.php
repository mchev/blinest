<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageReactionUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $messageId;

    public $reactions;

    public $userReaction;

    public function __construct($messageId, $reactions, $userReaction = null)
    {
        $this->messageId = $messageId;
        $this->reactions = $reactions;
        $this->userReaction = $userReaction;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('chat.message.'.$this->messageId);
    }

    public function broadcastWith()
    {
        return [
            'messageId' => $this->messageId,
            'reactions' => $this->reactions,
            'userReaction' => $this->userReaction,
        ];
    }
}
