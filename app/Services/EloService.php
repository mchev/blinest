<?php

namespace App\Services;

use App\Models\Round;
use App\Models\RoundStanding;
use App\Models\User;
use Illuminate\Support\Collection;

class EloService
{
    /**
     * Nombre de rounds de placement pour les nouveaux joueurs
     */
    private const PLACEMENT_ROUNDS = 10;

    /**
     * ELO initial pour les nouveaux joueurs
     */
    private const INITIAL_ELO = 1500;

    /**
     * ELO moyen cible pour la protection contre l'inflation/déflation
     */
    private const TARGET_AVERAGE_ELO = 1500;

    /**
     * Pourcentage minimum de tracks qu'un joueur doit avoir joué pour que son ELO soit compté
     * (0.8 = 80%)
     */
    private const MIN_TRACKS_PLAYED_PERCENTAGE = 0.8;

    /**
     * Calcule le changement d'ELO pour un joueur basé sur sa position et les ELO des adversaires
     * Utilise un calcul individuel contre chaque adversaire (plus précis)
     *
     * @param  int  $userElo  ELO actuel du joueur
     * @param  Collection  $opponentElos  Collection des ELO des autres joueurs (sans le joueur actuel)
     * @param  int  $position  Position finale du joueur (1 = 1er, 2 = 2ème, etc.)
     * @param  int  $totalPlayers  Nombre total de joueurs
     * @param  int  $placementRoundsPlayed  Nombre de rounds de placement joués (0 = nouveau joueur)
     * @return int Changement d'ELO (peut être négatif)
     */
    public function calculateEloChange(int $userElo, Collection $opponentElos, int $position, int $totalPlayers, int $placementRoundsPlayed = 0): int
    {
        if ($opponentElos->isEmpty()) {
            return 0;
        }

        // Score réel basé sur la position (1er = 1.0, 2ème = 0.66, 3ème = 0.33, etc.)
        $actualScore = $this->getActualScore($position, $totalPlayers);

        // Calculer le score attendu en comparant individuellement contre chaque adversaire
        // C'est plus précis que de comparer contre la moyenne
        $expectedScores = [];
        foreach ($opponentElos as $opponentElo) {
            $expectedScores[] = $this->getExpectedScore($userElo, $opponentElo);
        }
        $expectedScore = collect($expectedScores)->avg();

        // Obtenir le K-factor variable selon l'ELO et le nombre de rounds joués
        $kFactor = $this->getKFactor($userElo, $placementRoundsPlayed);

        // Calculer le changement d'ELO
        $eloChange = (int) round($kFactor * ($actualScore - $expectedScore));

        return $eloChange;
    }

    /**
     * Calcule le K-factor variable selon l'ELO et le nombre de rounds joués
     *
     * @param  int  $userElo  ELO actuel du joueur
     * @param  int  $placementRoundsPlayed  Nombre de rounds de placement joués
     * @return float K-factor
     */
    private function getKFactor(int $userElo, int $placementRoundsPlayed): float
    {
        // Pendant les rounds de placement, K-factor plus élevé pour ajuster rapidement
        if ($placementRoundsPlayed < self::PLACEMENT_ROUNDS) {
            // K-factor décroissant pendant le placement : 50 -> 40 -> 35 -> 32
            $placementK = 50 - ($placementRoundsPlayed * 1.8);

            return max(32, $placementK);
        }

        // Après le placement, K-factor variable selon l'ELO
        // Plus l'ELO est élevé, plus le K-factor est bas (moins de volatilité)
        if ($userElo < 1200) {
            return 40; // Débutants : plus de volatilité
        } elseif ($userElo < 1600) {
            return 32; // Intermédiaires : standard
        } elseif ($userElo < 2000) {
            return 24; // Avancés : moins de volatilité
        } else {
            return 16; // Experts : très peu de volatilité
        }
    }

    /**
     * Calcule le score réel basé sur la position finale
     *
     * @param  int  $position  Position finale (1 = 1er, 2 = 2ème, etc.)
     * @param  int  $totalPlayers  Nombre total de joueurs
     * @return float Score réel entre 0 et 1
     */
    private function getActualScore(int $position, int $totalPlayers): float
    {
        // Plus on est bien classé, plus le score est élevé
        // 1er = 1.0, 2ème = 0.66, 3ème = 0.33, etc.
        // Formule: (totalPlayers - position + 1) / totalPlayers
        return ($totalPlayers - $position + 1) / $totalPlayers;
    }

    /**
     * Calcule le score attendu basé sur la différence d'ELO
     *
     * @param  int  $userElo  ELO du joueur
     * @param  float  $opponentElo  ELO moyen des adversaires
     * @return float Score attendu entre 0 et 1
     */
    private function getExpectedScore(int $userElo, float $opponentElo): float
    {
        // Formule ELO standard: 1 / (1 + 10^((elo_adversaire - elo_joueur) / 400))
        $eloDifference = $opponentElo - $userElo;

        return 1 / (1 + pow(10, $eloDifference / 400));
    }

    /**
     * Crée les standings pour tous les joueurs d'un round
     * Met à jour l'ELO uniquement si les conditions sont remplies (room publique, 3+ joueurs)
     *
     * @param  Round  $round  Le round à traiter
     * @param  Collection|null  $podium  Podium depuis Redis (optionnel, sera calculé si null)
     * @param  array  $tracksHistory  Historique des tracks par user_id depuis Redis [userId => [['track_id' => int, 'response_time' => float, 'position' => int, 'score' => float], ...], ...]
     * @return array Tableau avec 'standings' et 'elo_updates' pour broadcaster après la transaction
     */
    public function updateElosForRound(Round $round, ?Collection $podium = null, array $tracksHistory = []): array
    {
        // S'assurer que la relation room est chargée
        if (! $round->relationLoaded('room')) {
            $round->load('room');
        }

        // Si podium n'est pas fourni, le calculer depuis les standings ou scores
        if ($podium === null) {
            // Essayer depuis les standings d'abord (si round terminé)
            if ($round->finished_at && $round->standings()->exists()) {
                $podium = $round->standings()
                    ->select('user_id', 'total_score as total', 'team_id')
                    ->orderByDesc('total_score')
                    ->get();
            } else {
                // Sinon, depuis les scores (compatibilité avec ancien système)
                $podium = $round->usersPodium()->get();
            }
        }

        // Si aucun joueur, ne rien faire
        if ($podium->isEmpty()) {
            return [
                'standings' => [],
                'elo_updates' => [],
            ];
        }

        // Vérifier les conditions pour compter l'ELO
        $isEloCounted = $this->shouldCountElo($round, $podium);

        // Charger les ELO actuels de tous les joueurs
        $userIds = $podium->pluck('user_id');
        $users = User::whereIn('id', $userIds)->get()->keyBy('id');

        // Récupérer les scores de tous les joueurs pour calculer les métriques de performance
        // Si on utilise Redis, les scores individuels peuvent ne plus exister
        // On utilise les standings ou on calcule depuis Redis
        $scoresByUser = collect();
        if ($round->scores()->exists()) {
            $scoresByUser = $round->scores()
                ->whereIn('user_id', $userIds)
                ->get()
                ->groupBy('user_id');
        }

        // Calculer le nombre total de tracks dans le round
        $totalTracksInRound = count($round->tracks ?? []);

        // Utiliser l'historique des tracks depuis Redis (fourni en paramètre)
        // Calculer le nombre de tracks écoutées depuis l'historique
        $tracksPlayedByUser = collect();
        if (! empty($tracksHistory)) {
            foreach ($tracksHistory as $userId => $history) {
                // Compter les tracks uniques (par track_id)
                $uniqueTrackIds = collect($history)->pluck('track_id')->unique();
                $tracksPlayedByUser[$userId] = $uniqueTrackIds->count();
            }
        } else {
            // Fallback : calculer depuis les scores DB (pour les anciens rounds)
            // Compter les tracks distinctes pour chaque joueur depuis les scores
            foreach ($userIds as $userId) {
                $userScores = $scoresByUser->get($userId, collect());
                $uniqueTrackIds = $userScores->pluck('track_id')->unique();
                $tracksPlayedByUser[$userId] = $uniqueTrackIds->count();
            }
        }

        // Récupérer la durée des tracks pour calculer les réponses rapides
        $trackDuration = $round->room->track_duration ?? 30;
        $speedBonusThreshold = $trackDuration * 0.18; // 18% de la durée

        // Préparer les données pour le calcul
        $standings = [];
        $totalPlayers = $podium->count();

        // Créer une collection des joueurs valides (qui ont joué assez de tracks) pour le calcul d'ELO
        $validPlayersForElo = collect();
        foreach ($podium as $podiumEntry) {
            $userId = $podiumEntry->user_id;
            $tracksPlayed = $tracksPlayedByUser->get($userId, 0);
            $tracksPlayedPercentage = $totalTracksInRound > 0
                ? $tracksPlayed / $totalTracksInRound
                : 0;
            $user = $users->get($userId);
            $isGuest = $user && $user->isGuest();
            $hasPlayedEnoughTracks = $tracksPlayedPercentage >= self::MIN_TRACKS_PLAYED_PERCENTAGE;

            if ($hasPlayedEnoughTracks && ! $isGuest) {
                $validPlayersForElo->push($podiumEntry);
            }
        }

        // Nombre de joueurs valides pour le calcul d'ELO
        $totalValidPlayers = $validPlayersForElo->count();

        // Si moins de 3 joueurs valides, ne pas compter l'ELO pour personne
        if ($totalValidPlayers < 3) {
            $isEloCounted = false;
        }

        foreach ($podium as $index => $podiumEntry) {
            $userId = $podiumEntry->user_id;
            $user = $users->get($userId);

            if (! $user) {
                continue;
            }

            $position = $index + 1;
            $userElo = $user->elo ?? self::INITIAL_ELO;
            $totalScore = (float) $podiumEntry->total;

            // Calculer les métriques de performance
            // Si on utilise Redis, les scores individuels peuvent ne plus exister
            // On calcule les métriques depuis les scores DB si disponibles, sinon valeurs par défaut
            $userScores = $scoresByUser->get($userId, collect());
            if ($userScores->isEmpty()) {
                // Pas de scores individuels (utilisation de Redis), métriques par défaut
                $performanceMetrics = [
                    'average_response_time' => null,
                    'fast_answers_count' => 0,
                    'total_answers_count' => 0,
                ];
            } else {
                $performanceMetrics = $this->calculatePerformanceMetrics($userScores, $speedBonusThreshold);
            }

            // Calculer le win streak
            $winStreak = $this->calculateWinStreak($round, $userId, $position);

            // Vérifier si le joueur a joué assez de tracks pour que son ELO soit compté
            $tracksPlayed = $tracksPlayedByUser->get($userId, 0);
            $tracksPlayedPercentage = $totalTracksInRound > 0
                ? $tracksPlayed / $totalTracksInRound
                : 0;
            $hasPlayedEnoughTracks = $tracksPlayedPercentage >= self::MIN_TRACKS_PLAYED_PERCENTAGE;

            // Calculer l'ELO seulement si on doit le compter ET si le joueur a joué assez de tracks
            $isEloCountedForUser = $isEloCounted && $hasPlayedEnoughTracks;

            if ($isEloCountedForUser) {
                // Compter le nombre de rounds de placement joués par ce joueur
                $placementRoundsPlayed = $this->getPlacementRoundsPlayed($userId);

                // Récupérer les ELO des autres joueurs valides (adversaires qui ont aussi joué assez de tracks)
                $opponentElos = $validPlayersForElo->reject(function ($entry) use ($userId) {
                    return $entry->user_id === $userId;
                })->map(function ($entry) use ($users) {
                    $opponentUser = $users->get($entry->user_id);

                    return $opponentUser ? ($opponentUser->elo ?? self::INITIAL_ELO) : self::INITIAL_ELO;
                });

                // Calculer la position parmi les joueurs valides seulement
                // Trier les joueurs valides par score total décroissant pour obtenir la position correcte
                $sortedValidPlayers = $validPlayersForElo->sortByDesc('total')->values();
                $validPosition = $sortedValidPlayers->search(function ($entry) use ($userId) {
                    return $entry->user_id === $userId;
                });
                $validPosition = $validPosition !== false ? $validPosition + 1 : $position;

                // Calculer le changement d'ELO avec le nouveau système
                // Utiliser la position parmi les joueurs valides et le nombre de joueurs valides
                $eloChange = $this->calculateEloChange($userElo, $opponentElos, $validPosition, $totalValidPlayers, $placementRoundsPlayed);

                // Appliquer la protection contre l'inflation/déflation
                $eloChange = $this->applyInflationProtection($eloChange, $userElo);

                // Ajuster le changement d'ELO proportionnellement au ratio de tracks jouées
                // Si le joueur a joué 90% des tracks, il reçoit 90% du changement d'ELO
                // Cela permet aux joueurs arrivés en milieu de partie de faire évoluer leur ELO
                // mais de manière proportionnelle au nombre de tracks jouées
                $eloChange = (int) round($eloChange * $tracksPlayedPercentage);

                $eloAfter = $userElo + $eloChange;
            } else {
                // Pas de changement d'ELO si les conditions ne sont pas remplies
                $eloChange = 0;
                $eloAfter = $userElo;
            }

            // Récupérer l'historique des tracks depuis Redis
            // Si vide, construire depuis les scores DB (pour les anciens rounds)
            $userTracksHistory = $tracksHistory[$userId] ?? [];
            if (empty($userTracksHistory) && $scoresByUser->has($userId)) {
                // Construire l'historique depuis les scores DB pour compatibilité
                $userScores = $scoresByUser->get($userId);
                $userTracksHistory = $userScores->map(function ($score) {
                    return [
                        'track_id' => $score->track_id,
                        'answer_id' => $score->answer_id,
                        'response_time' => $score->time,
                        'position' => null, // Ne peut pas être calculé sans comparer avec les autres
                        'score' => $score->score,
                    ];
                })->values()->toArray();
            }

            $standings[] = [
                'round_id' => $round->id,
                'room_id' => $round->room_id,
                'user_id' => $userId,
                'team_id' => $podiumEntry->team_id ?? null,
                'position' => $position,
                'total_score' => $totalScore,
                'elo_before' => $userElo,
                'elo_after' => $eloAfter,
                'elo_change' => $eloChange,
                'is_elo_counted' => $isEloCountedForUser,
                'average_response_time' => $performanceMetrics['average_response_time'],
                'fast_answers_count' => $performanceMetrics['fast_answers_count'],
                'total_answers_count' => $performanceMetrics['total_answers_count'],
                'tracks_history' => $userTracksHistory,
                'win_streak' => $winStreak,
            ];
        }

        // Enregistrer les standings et mettre à jour les ELO des utilisateurs (seulement si is_elo_counted)
        // Note: Cette méthode est appelée depuis une transaction dans ProcessRoundElo
        // On ne crée pas de nouvelle transaction ici pour éviter les transactions imbriquées
        $eloUpdates = [];

        foreach ($standings as $standingData) {
            RoundStanding::create($standingData);

            // Mettre à jour l'ELO de l'utilisateur seulement si is_elo_counted = true
            // (cela inclut maintenant la vérification du pourcentage de tracks jouées)
            if ($standingData['is_elo_counted']) {
                $user = $users->get($standingData['user_id']);
                if ($user) {
                    $user->update(['elo' => $standingData['elo_after']]);
                    // Stocker les informations pour broadcaster après la transaction
                    $eloUpdates[] = [
                        'user' => $user,
                        'elo' => $standingData['elo_after'],
                    ];
                }
            }
        }

        // Retourner les standings et les mises à jour d'ELO pour broadcaster après la transaction
        return [
            'standings' => $standings,
            'elo_updates' => $eloUpdates,
        ];
    }

    /**
     * Détermine si l'ELO doit être compté pour ce round
     */
    private function shouldCountElo(Round $round, Collection $podium): bool
    {
        // Vérifier que la room est publique
        if (! $round->room || ! $round->room->is_public) {
            return false;
        }

        // Vérifier qu'il y a au moins 3 joueurs
        if ($podium->count() < 3) {
            return false;
        }

        return true;
    }

    /**
     * Calcule les métriques de performance pour un joueur
     *
     * @param  Collection  $userScores  Collection des scores du joueur pour ce round
     * @param  float  $speedBonusThreshold  Seuil pour considérer une réponse comme rapide (en secondes)
     */
    private function calculatePerformanceMetrics(Collection $userScores, float $speedBonusThreshold): array
    {
        if ($userScores->isEmpty()) {
            return [
                'average_response_time' => null,
                'fast_answers_count' => 0,
                'total_answers_count' => 0,
            ];
        }

        // Filtrer les scores qui ont un temps (time n'est pas null)
        $scoresWithTime = $userScores->filter(fn ($score) => $score->time !== null);

        // Calculer le temps moyen de réponse
        $averageResponseTime = $scoresWithTime->isNotEmpty()
            ? $scoresWithTime->avg('time')
            : null;

        // Compter les réponses rapides (time < seuil)
        $fastAnswersCount = $scoresWithTime->filter(function ($score) use ($speedBonusThreshold) {
            return $score->time < $speedBonusThreshold;
        })->count();

        // Nombre total de réponses
        $totalAnswersCount = $userScores->count();

        return [
            'average_response_time' => $averageResponseTime ? round($averageResponseTime, 3) : null,
            'fast_answers_count' => $fastAnswersCount,
            'total_answers_count' => $totalAnswersCount,
        ];
    }

    /**
     * Calcule le win streak (nombre de victoires consécutives) pour un joueur dans une room
     *
     * @param  Round  $round  Round actuel
     * @param  int  $userId  ID du joueur
     * @param  int  $position  Position actuelle du joueur (1 = victoire)
     * @return int Nombre de victoires consécutives
     */
    private function calculateWinStreak(Round $round, int $userId, int $position): int
    {
        // Si le joueur n'est pas 1er, le streak est 0
        if ($position !== 1) {
            return 0;
        }

        // Limiter à 200 rounds précédents pour éviter les problèmes de placeholders SQL
        // (MySQL limite à 65,535 placeholders par requête)
        // 200 rounds est largement suffisant pour calculer un win streak
        $maxPreviousRounds = 200;

        // Récupérer les standings des rounds précédents pour ce joueur dans cette room
        // On utilise directement une requête sur RoundStanding avec une limite
        // pour éviter de charger tous les IDs de rounds en mémoire
        $previousStandings = RoundStanding::where('room_id', $round->room_id)
            ->where('user_id', $userId)
            ->whereHas('round', function ($query) use ($round) {
                $query->where('id', '<', $round->id)
                    ->whereNotNull('finished_at');
            })
            ->orderByDesc('round_id')
            ->limit($maxPreviousRounds)
            ->get(['round_id', 'position']);

        // Si le joueur n'a pas de standings dans les rounds précédents, c'est sa première victoire
        if ($previousStandings->isEmpty()) {
            return 1;
        }

        // Compter les victoires consécutives en partant du round le plus récent
        $streak = 1; // On commence à 1 car le joueur est 1er dans ce round

        foreach ($previousStandings as $standing) {
            if ($standing->position === 1) {
                $streak++;
            } else {
                // Dès qu'on trouve une position != 1, on arrête le streak
                break;
            }
        }

        return $streak;
    }

    /**
     * Compte le nombre de rounds de placement joués par un utilisateur
     * (rounds avec is_elo_counted = true)
     */
    private function getPlacementRoundsPlayed(int $userId): int
    {
        return RoundStanding::where('user_id', $userId)
            ->where('is_elo_counted', true)
            ->count();
    }

    /**
     * Applique une protection contre l'inflation/déflation de l'ELO
     * Ajuste légèrement le changement pour maintenir une moyenne stable
     *
     * @param  int  $eloChange  Changement d'ELO calculé
     * @param  int  $userElo  ELO actuel du joueur
     * @return int Changement d'ELO ajusté
     */
    private function applyInflationProtection(int $eloChange, int $userElo): int
    {
        // Si l'ELO est très élevé (> 2000) et que le changement est positif, réduire légèrement
        // Cela empêche l'inflation excessive
        if ($userElo > 2000 && $eloChange > 0) {
            $reductionFactor = 0.9; // Réduire de 10%
            $eloChange = (int) round($eloChange * $reductionFactor);
        }

        // Si l'ELO est très bas (< 1000) et que le changement est négatif, réduire la perte
        // Cela empêche la déflation excessive
        if ($userElo < 1000 && $eloChange < 0) {
            $reductionFactor = 0.9; // Réduire la perte de 10%
            $eloChange = (int) round($eloChange * $reductionFactor);
        }

        return $eloChange;
    }
}
