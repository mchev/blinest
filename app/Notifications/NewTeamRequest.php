<?php

namespace App\Notifications;

use App\Models\TeamRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewTeamRequest extends Notification implements ShouldQueue
{
    use Queueable;

    public $teamRequest;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TeamRequest $teamRequest)
    {
        $this->teamRequest = $teamRequest;
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
                'teamRequest' => $this->teamRequest,
                'message' => '@'.$this->teamRequest->user->name.' '.__('wishes to join your team'),
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
            'teamRequest' => $this->teamRequest,
            'message' => '@'.$this->teamRequest->user->name.__(' wishes to join your team'),
        ];
    }
}
