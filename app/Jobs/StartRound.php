<?php

namespace App\Jobs;

use App\Events\RoundFinished;
use App\Events\RoundStarted;
use App\Models\Room;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
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
            // Check if there's already an active round for this room
            $activeRound = $this->room->currentRound()->first();
            if ($activeRound && $activeRound->is_playing) {
                Log::info('Round already active for room', [
                    'room_id' => $this->room->id,
                    'round_id' => $activeRound->id,
                ]);

                return; // Don't create a new round if one is already playing
            }

            $round = $this->room->rounds()->create([
                'user_id' => $this->user ? $this->user->id : null,
                'is_playing' => true,
                'tracks' => $this->room->is_random
                    ? $this->getRandomTracks()
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
                $round->update([
                    'is_playing' => false,
                    'finished_at' => now(),
                ]);
                $round->room->update([
                    'is_playing' => false,
                ]);
                broadcast(new RoundFinished($round));
                Log::error('No tracks available for the room. Round aborted.');
            }
        } catch (\Exception $e) {
            // Handle any other exceptions that may occur during the round start process
            Log::error('Error starting round: '.$e->getMessage());
        }
    }

    /**
     * Get random tracks efficiently for large datasets
     */
    private function getRandomTracks()
    {
        $count = $this->room->tracks()->count();
        $limit = $this->room->tracks_by_round;

        // If we have fewer tracks than needed, return all tracks
        if ($count <= $limit) {
            return $this->room->tracks()->pluck('id');
        }

        // Get random tracks using offset
        $tracks = collect();
        $usedOffsets = collect();

        while ($tracks->count() < $limit) {
            // Generate a random offset
            $offset = rand(0, $count - 1);

            // Skip if we've already used this offset
            if ($usedOffsets->contains($offset)) {
                continue;
            }

            // Get the track at this offset
            $track = $this->room->tracks()
                ->select('id')
                ->skip($offset)
                ->take(1)
                ->first();

            if ($track) {
                $tracks->push($track->id);
                $usedOffsets->push($offset);
            }
        }

        return $tracks;
    }
}
