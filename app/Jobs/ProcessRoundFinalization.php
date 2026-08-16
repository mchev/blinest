<?php

namespace App\Jobs;

use App\Events\RoomPublicState;
use App\Events\RoundFinished;
use App\Events\UserEloUpdated;
use App\Models\Round;
use App\Services\RoundFinalizationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessRoundFinalization implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Round $round
    ) {}

    public function handle(RoundFinalizationService $roundFinalizationService): void
    {
        try {
            $result = $roundFinalizationService->finalize($this->round);

            if (! $result->processed) {
                return;
            }

            $this->round->refresh()->load('room');

            foreach ($result->eloUpdates as $update) {
                broadcast(new UserEloUpdated($update['user'], $this->round->room, $update['elo']));
            }

            broadcast(new RoundFinished($this->round));
            broadcast(new RoomPublicState($this->round->room));
        } catch (\Throwable $e) {
            Log::error('ProcessRoundFinalization: Error processing round', [
                'round_id' => $this->round->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }
}
