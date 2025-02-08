<?php

namespace App\Jobs;

use App\Models\Track;
use App\Notifications\TrackDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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

        $title = $this->track->answers()->where('answer_type_id', 2)->first()?->value ?? 'inconnu';
        $artist = $this->track->answers()->where('answer_type_id', 1)->first()?->value ?? 'inconnu';
        $message = "Le titre {$title} de {$artist} a été supprimé.";

        try {
            // Public rooms discord notification
            foreach ($this->track->playlist->rooms()->isPublic()->get() as $room) {
                if ($room->discord_webhook_url) {
                    SendDiscordNotification::dispatch($room, $message, 'danger');
                }
            }

            // Private rooms notifications
            if (! $this->track->playlist->is_public) {
                foreach ($this->track->playlist->moderators as $moderator) {
                    $moderator->notify(new TrackDeleted($this->track->playlist, $message));
                    Cache::forget($moderator->id.'_unread_notifications');
                }
            }

            $this->track->answers()->delete();
            $this->track->delete();
        } catch (\Exception $e) {
            Log::error('Error while processing failing track', [
                'track_id' => $this->track->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
