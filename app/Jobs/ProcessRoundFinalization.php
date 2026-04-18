<?php

namespace App\Jobs;

use App\Events\UserEloUpdated;
use App\Models\Round;
use App\Models\Team;
use App\Models\TotalScore;
use App\Models\User;
use App\Services\EloService;
use App\Services\RoundScoreService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessRoundFinalization implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Round $round
    ) {}

    /**
     * Execute the job.
     * Agrège tous les scores depuis Redis et crée les standings en batch
     */
    public function handle(RoundScoreService $roundScoreService, EloService $eloService): void
    {
        // Rafraîchir le round pour s'assurer d'avoir les dernières données
        $this->round->refresh();

        // Charger la relation room
        $this->round->load('room');

        // Vérifier que le round est terminé
        if (! $this->round->finished_at) {
            Log::warning('ProcessRoundFinalization: Round not finished', [
                'round_id' => $this->round->id,
            ]);

            return;
        }

        // Vérifier qu'il n'y a pas déjà des standings pour ce round
        if ($this->round->standings()->exists()) {
            Log::info('ProcessRoundFinalization: Standings already exist for this round', [
                'round_id' => $this->round->id,
            ]);

            return;
        }

        $eloUpdates = [];

        try {
            // Utiliser un lock pour éviter les race conditions
            DB::transaction(function () use ($roundScoreService, $eloService, &$eloUpdates) {
                // Vérifier avec lock pour éviter les doublons
                $lock = DB::table('rounds')
                    ->where('id', $this->round->id)
                    ->lockForUpdate()
                    ->first();

                if (! $lock) {
                    Log::warning('ProcessRoundFinalization: Round not found', [
                        'round_id' => $this->round->id,
                    ]);

                    return;
                }

                // 1. Récupérer tous les scores depuis Redis
                $scores = $roundScoreService->getAllScores($this->round->id);
                $tracksHistory = $roundScoreService->getAllTracksHistory($this->round->id);

                // Si aucun score dans Redis, fallback vers les scores DB (compatibilité avec anciens rounds)
                if (empty($scores)) {
                    // Utiliser l'ancien système ProcessRoundElo pour compatibilité
                    Log::info('ProcessRoundFinalization: No scores in Redis, falling back to DB scores', [
                        'round_id' => $this->round->id,
                    ]);

                    // Utiliser l'ancien job pour les rounds qui n'ont pas utilisé Redis
                    ProcessRoundElo::dispatch($this->round)->afterCommit();

                    return;
                }

                // 2. Charger les users et teams pour avoir les relations
                $userIds = array_keys($scores);
                $users = User::whereIn('id', $userIds)->with('team')->get()->keyBy('id');

                // 3. Créer le podium depuis Redis (déjà trié par score décroissant)
                $podiumData = [];
                foreach ($scores as $userId => $total) {
                    $user = $users->get($userId);
                    $podiumData[] = (object) [
                        'user_id' => (int) $userId,
                        'total' => $total,
                        'team_id' => $user?->team?->id,
                    ];
                }
                // Trier par score décroissant
                usort($podiumData, fn ($a, $b) => $b->total <=> $a->total);
                $podium = collect($podiumData);

                // 4. Calculer ELO et créer les standings
                // Calculer les positions pour chaque track dans l'historique
                $this->calculateTrackPositions($tracksHistory, $scores);

                // Passer l'historique complet à EloService
                $result = $eloService->updateElosForRound($this->round, $podium, $tracksHistory);
                $standings = $result['standings'];
                $eloUpdates = $result['elo_updates'];

                // 5. Mettre à jour TotalScores en batch
                $this->updateTotalScoresBatch($scores, $users);

                // 6. Nettoyer Redis
                $roundScoreService->cleanup($this->round->id);
            });

            // Broadcaster les événements de mise à jour d'ELO après la transaction
            foreach ($eloUpdates as $update) {
                broadcast(new UserEloUpdated($update['user'], $this->round->room, $update['elo']));
            }
        } catch (\Exception $e) {
            Log::error('ProcessRoundFinalization: Error processing round', [
                'round_id' => $this->round->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Calcule les positions pour chaque track dans l'historique
     * En comparant les scores de chaque joueur pour chaque track
     */
    private function calculateTrackPositions(array &$tracksHistory, array $scores): void
    {
        // Si l'historique est vide, ne rien faire (cas des anciens rounds)
        if (empty($tracksHistory)) {
            return;
        }

        // Pour chaque track, on doit comparer les scores de tous les joueurs
        // On groupe par track_id pour calculer les positions
        $tracksByTrackId = [];

        foreach ($tracksHistory as $userId => $history) {
            foreach ($history as $trackData) {
                $trackId = $trackData['track_id'];
                if (! isset($tracksByTrackId[$trackId])) {
                    $tracksByTrackId[$trackId] = [];
                }
                $tracksByTrackId[$trackId][] = [
                    'user_id' => $userId,
                    'score' => $trackData['score'] ?? 0,
                    'response_time' => $trackData['response_time'] ?? null,
                ];
            }
        }

        // Pour chaque track, trier par score décroissant et assigner les positions
        foreach ($tracksByTrackId as $trackId => $players) {
            // Trier par score décroissant, puis par temps croissant (meilleur temps = meilleur)
            usort($players, function ($a, $b) {
                if ($a['score'] != $b['score']) {
                    return $b['score'] <=> $a['score'];
                }
                // Si même score, meilleur temps = meilleur position
                if ($a['response_time'] !== null && $b['response_time'] !== null) {
                    return $a['response_time'] <=> $b['response_time'];
                }

                return 0;
            });

            // Assigner les positions
            foreach ($players as $position => $player) {
                $userId = $player['user_id'];
                // Trouver et mettre à jour la position dans l'historique
                if (isset($tracksHistory[$userId])) {
                    foreach ($tracksHistory[$userId] as &$trackData) {
                        if ($trackData['track_id'] == $trackId) {
                            $trackData['position'] = $position + 1;
                        }
                    }
                }
            }
        }
    }

    /**
     * Met à jour TotalScores en batch pour tous les joueurs
     */
    private function updateTotalScoresBatch(array $scores, $users): void
    {
        $room = $this->round->room;
        if (! $room) {
            return;
        }

        $updates = [];

        foreach ($scores as $userId => $score) {
            $user = $users->get($userId);
            if (! $user) {
                continue;
            }

            // Mettre à jour le score total de l'utilisateur pour cette room
            TotalScore::updateOrCreate(
                [
                    'totalscorable_type' => User::class,
                    'totalscorable_id' => $userId,
                    'room_id' => $room->id,
                ],
                []
            )->increment('score', $score);

            // Mettre à jour le score total de l'équipe si présente
            if ($user->team) {
                TotalScore::updateOrCreate(
                    [
                        'totalscorable_type' => Team::class,
                        'totalscorable_id' => $user->team->id,
                        'room_id' => $room->id,
                    ],
                    []
                )->increment('score', $score);
            }

            // Mettre à jour le niveau utilisateur si room publique (en batch à la fin)
            if ($room->is_public) {
                // On peut dispatcher un job pour mettre à jour le niveau, mais en batch
                // Pour l'instant, on le fait individuellement mais on pourrait optimiser
                UpdateUserLevel::dispatch(
                    user: $user,
                    type: 'score'
                );
            }
        }
    }
}
