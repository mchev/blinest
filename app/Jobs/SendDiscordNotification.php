<?php

namespace App\Jobs;

use App\Models\Room;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendDiscordNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public Room $room, public $message, public $type)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->room->discord_webhook_url) {
            if ($this->type === 'danger') {
                $color = '15158332';
            } elseif ($this->type === 'success') {
                $color = '3066993';
            } else {
                $color = '3447003';
            }

            Http::post($this->room->discord_webhook_url, [
                'embeds' => [
                    [
                        'title' => $this->room->name,
                        'description' => $this->message,
                        'color' => $color,
                    ],
                ],
            ]);
        }
    }
}
