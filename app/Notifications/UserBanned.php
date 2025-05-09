<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserBanned extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private string $reason,
        private ?int $duration = null
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Bannissement')
            ->line('Vous avez été banni de la plateforme.')
            ->line('Raison : '.$this->reason);

        if ($this->duration) {
            $durationText = $this->duration >= 60
                ? floor($this->duration / 60).' heure(s)'
                : $this->duration.' minute(s)';
            $message->line('Durée : '.$durationText);
        } else {
            $message->line('Ce bannissement est permanent.');
        }

        return $message;
    }

    public function toArray($notifiable): array
    {
        return [
            'reason' => $this->reason,
            'duration' => $this->duration,
            'type' => 'ban',
        ];
    }
}
