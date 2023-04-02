<?php

namespace App\Notifications;

use App\Models\Room;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewRoomAlert extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public Room $room, public User $user, public $message = null)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        $body = $this->message
            ? $this->user->name.' signale un problème sur le chat '.$this->room->name.' : '.$this->message
            : $this->user->name.' signale un problème sur le chat '.$this->room->name;

        return new BroadcastMessage([
            'data' => [
                'room' => $this->room,
                'created_at' => now()->format('d/m/Y H:i'),
                'message' => $body,
            ],
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $body = $this->message
            ? $this->user->name.' '.__('reports a problem on the chat').' '.$this->room->name.' : '.$this->message
            : $this->user->name.' '.__('reports a problem on the chat').' '.$this->room->name;

        return [
            'room' => $this->room,
            'created_at' => now()->format('d/m/Y H:i'),
            'message' => $body,
        ];
    }
}
