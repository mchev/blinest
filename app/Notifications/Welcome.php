<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Welcome extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('messages.welcome_subject'))
            ->greeting(__('messages.welcome_title', ['name' => $notifiable->name]))
            ->line(__('messages.welcome_intro'))
            ->line(__('messages.welcome_content'))
            ->action(__('messages.welcome_action'), url('/'))
            ->salutation(__('messages.salutations'));
    }
}
