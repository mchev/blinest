<?php

namespace App\Jobs;

use App\Models\Track;
use App\Models\User;
use App\Notifications\TrackDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProcessDeletedTrack implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Track $track,
        private ?User $user = null
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $title = $this->track->answers()->where('answer_type_id', 2)->first()?->value ?? 'inconnu';
        $artist = $this->track->answers()->where('answer_type_id', 1)->first()?->value ?? 'inconnu';

        if ($this->user) {
            $message = "Le titre {$title} de {$artist} a été supprimé par {$this->user->name}.";
        } else {
            $message = "Le titre {$title} de {$artist} a été supprimé automatiquement car l'aperçu n'est plus disponible sur {$this->track->provider}.";
        }

        try {
            // Only send notifications in production
            if (app()->environment('production')) {
                // Public rooms discord notification
                foreach ($this->track->playlist->rooms()->isPublic()->get() as $room) {
                    if ($room->discord_webhook_url) {
                        SendDiscordNotification::dispatch($room, $message, 'danger');
                    }
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
