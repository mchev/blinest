<?php

namespace App\Notifications;

use App\Models\Room;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewSuggestion extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public Room $room, public string $suggestion, protected User $user)
    {

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     */
    public function via($notifiable): array
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
                'room' => [
                    'id' => $this->room->id,
                    'name' => $this->room->name,
                    'playlists' => implode(',', $this->room->playlists()->pluck('name')->toArray()),
                ],
                'user' => [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                ],
                'created_at' => now()->format('d/m/Y H:i'),
                'message' => $this->suggestion,
            ],
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toArray($notifiable): array
    {
        return [
            'room' => [
                'id' => $this->room->id,
                'name' => $this->room->name,
                'playlists' => implode(',', $this->room->playlists()->pluck('name')->toArray()),
            ],
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'created_at' => now()->format('d/m/Y H:i'),
            'message' => $this->suggestion,
        ];
    }
}
