<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactMessage extends Notification implements ShouldQueue
{
    use Queueable;

    public $message;

    public $email;

    private ?User $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(?User $user, string $email, string $message)
    {
        $this->user = $user;
        $this->email = $email;
        $this->message = $message;
    }

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
        $mailMessage = (new MailMessage)
            ->subject('Message de '.($this->user?->name ?? 'Visiteur'))
            ->replyTo($this->email)
            ->from($this->email);

        if ($this->user) {
            $mailMessage->line($this->user->name.': '.$this->user->email);
        }

        return $mailMessage->line($this->message);
    }
}
