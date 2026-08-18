<?php

namespace App\Notifications;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DonationThankYou extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Donation $donation) {}

    /**
     * @return list<string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $amount = number_format($this->donation->amount_cents / 100, 2, ',', ' ');

        return (new MailMessage)
            ->subject(__('Donation thank you subject'))
            ->greeting(__('Donation thank you greeting', ['name' => $notifiable->name]))
            ->line(__('Donation thank you body', ['amount' => $amount]))
            ->action(__('Donation thank you action'), route('docs.support'))
            ->salutation(__('messages.salutations'));
    }
}
