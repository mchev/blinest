<?php

namespace App\Jobs;

use App\Events\RoundStarted;
use App\Models\Room;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class StartRound implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds after which the job's unique lock will be released.
     *
     * @var int
     */
    public $uniqueFor = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Room $room, protected ?User $user = null)
    {
        //
    }

    /**
     * Get the unique ID for the job.
     */
    public function uniqueId(): string
    {
        return $this->room->id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {

            $round = $this->room->rounds()->create([
                'user_id' => $this->user ? $this->user->id : null,
                'is_playing' => true,
                'tracks' => $this->room->is_random
                    ? $this->room->tracks()->inRandomOrder()->take($this->room->tracks_by_round)->distinct()->pluck('id')
                    : $this->room->tracks()->take($this->room->tracks_by_round)->distinct()->pluck('id'),
            ]);

            if (! empty($round->tracks)) {
                $this->room->update([
                    'is_playing' => true,
                ]);

                broadcast(new RoundStarted($round));

                // Play the first track
                $round->playNextTrack();
            } else {
                $round->delete();
                Log::error('No tracks available for the room. Round aborted.');
            }
        } catch (\Exception $e) {
            // Handle any other exceptions that may occur during the round start process
            Log::error('Error starting round: '.$e->getMessage());
        }
    }
}
