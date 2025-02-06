<?php

namespace App\Jobs;

use App\Models\Track;
use App\Notifications\TrackDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;

class ProcessFailingTrack implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Track $track,
        public string $error
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        Log::error('Failed to play track', [
            'track_id' => $this->track->id,
            'round_id' => $this->track->round_id,
            'error' => $this->error,
        ]);

        $message = 'Le titre '.$track->answers()->where('answer_type_id', 2)->first()?->value.' de '.$track->answers()->where('answer_type_id', 1)->first()?->value.' a été supprimé.';

        // Public rooms discord notification
        foreach ($track->playlist->rooms()->isPublic()->get() as $room) {
            if ($room->discord_webhook_url) {
                SendDiscordNotification::dispatch($room, $message, 'danger');
            }
        }

        // Private rooms notifications
        if (! $track->playlist->is_public) {
            foreach ($track->playlist->moderators as $moderator) {
                $moderator->notify(new TrackDeleted($track->playlist, $message));
                Cache::forget($moderator->id.'_unread_notifications');
            }
        }

        $track->answers()->delete();
        $track->delete();
    }
}
