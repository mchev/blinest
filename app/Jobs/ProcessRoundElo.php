<?php

namespace App\Jobs;

use App\Events\UserEloUpdated;
use App\Models\Round;
use App\Services\EloService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessRoundElo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Round $round
    ) {}

    /**
     * Execute the job.
     */
    public function handle(EloService $eloService): void
    {
        // Rafraîchir le round pour s'assurer d'avoir les dernières données
        $this->round->refresh();

        // Charger la relation room
        $this->round->load('room');

        // Vérifier que le round est terminé
        if (! $this->round->finished_at) {
            Log::warning('ProcessRoundElo: Round not finished', [
                'round_id' => $this->round->id,
            ]);

            return;
        }

        $eloUpdates = [];

        try {
            // Utiliser un lock pour éviter les race conditions
            DB::transaction(function () use ($eloService, &$eloUpdates) {
                // Vérifier avec lock pour éviter les doublons
                $lock = DB::table('rounds')
                    ->where('id', $this->round->id)
                    ->lockForUpdate()
                    ->first();

                if (! $lock) {
                    Log::warning('ProcessRoundElo: Round not found', [
                        'round_id' => $this->round->id,
                    ]);

                    return;
                }

                // Vérifier qu'il n'y a pas déjà des standings pour ce round
                if ($this->round->standings()->exists()) {
                    Log::info('ProcessRoundElo: Standings already exist for this round', [
                        'round_id' => $this->round->id,
                    ]);

                    return;
                }

                // Toujours créer les standings (même pour rooms privées ou < 3 joueurs)
                // Le service déterminera si is_elo_counted = true ou false
                $result = $eloService->updateElosForRound($this->round);
                $standings = $result['standings'];
                $eloUpdates = $result['elo_updates'];

                // Vérifier que les standings ont bien été créés avant de supprimer les scores
                if (empty($standings)) {
                    Log::warning('ProcessRoundElo: No standings created, skipping score cleanup', [
                        'round_id' => $this->round->id,
                    ]);

                    return;
                }

                // Vérifier que tous les standings ont un total_score valide
                $standingsWithoutTotal = collect($standings)->filter(function ($standing) {
                    return ! isset($standing['total_score']) || $standing['total_score'] === null;
                });

                if ($standingsWithoutTotal->isNotEmpty()) {
                    Log::error('ProcessRoundElo: Some standings missing total_score, aborting cleanup', [
                        'round_id' => $this->round->id,
                        'count' => $standingsWithoutTotal->count(),
                    ]);
                    throw new \Exception('Cannot cleanup scores: some standings are missing total_score');
                }

                // Toujours nettoyer les scores individuels après avoir créé les standings
                // Les standings contiennent déjà le total_score pour chaque joueur
                // Même si standings est vide (podium vide), on nettoie quand même les scores orphelins
                $this->cleanupIndividualScores();
            });

            // Broadcaster les événements de mise à jour d'ELO après la transaction
            foreach ($eloUpdates as $update) {
                broadcast(new UserEloUpdated($update['user'], $this->round->room, $update['elo']));
            }
        } catch (\Exception $e) {
            Log::error('ProcessRoundElo: Error processing round ELO', [
                'round_id' => $this->round->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Nettoie les scores individuels après avoir enregistré les standings
     */
    private function cleanupIndividualScores(): void
    {
        // Supprimer tous les scores individuels de ce round
        // Les standings contiennent déjà le total_score pour chaque joueur
        $deletedCount = $this->round->scores()->delete();

        Log::info('ProcessRoundElo: Cleaned up individual scores', [
            'round_id' => $this->round->id,
            'deleted_scores_count' => $deletedCount,
        ]);
    }
}
