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

    public $room;

    public $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Room $room, User $user)
    {
        $this->room = $room;
        $this->user = $user;
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
        return new BroadcastMessage([
            'data' => [
                'room' => $this->room,
                'created_at' => now()->format('d/m/Y H:i'),
                'message' => $this->user->name.' signale un problème sur le chat '.$this->room->name,
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
        return [
            'room' => $this->room,
            'created_at' => now()->format('d/m/Y H:i'),
            'message' => $this->user->name.' signale un problème sur le chat '.$this->room->name,
        ];
    }
}
