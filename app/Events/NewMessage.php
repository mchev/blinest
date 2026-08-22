<?php

namespace App\Events;

use App\Models\Message;
use App\Services\Donations\DonorPerkService;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message->load('user');
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel($this->message->channel);
    }

    /**
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        $enriched = app(DonorPerkService::class)->enrichMessagesForChat([$this->message]);

        return [
            'message' => $enriched[0] ?? $this->message->toArray(),
        ];
    }
}
