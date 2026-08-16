<?php

namespace App\Services;

use App\Models\Score;
use Illuminate\Support\Facades\Redis;

class RoundScoreService
{
    /**
     * TTL pour les clés Redis (1 heure)
     */
    private const REDIS_TTL = 3600;

    private function playersKey(int $roundId): string
    {
        return "round:{$roundId}:players";
    }

    private function trackPlayer(int $roundId, int $userId): void
    {
        $key = $this->playersKey($roundId);
        Redis::sadd($key, $userId);
        Redis::expire($key, self::REDIS_TTL);
    }

    /**
     * @return list<int>
     */
    private function playerIds(int $roundId): array
    {
        $playerIds = Redis::smembers($this->playersKey($roundId));

        return array_map('intval', $playerIds ?: []);
    }

    /**
     * Ajoute un score pour un joueur dans un round
     */
    public function addScore(int $roundId, int $userId, float $score): void
    {
        $this->trackPlayer($roundId, $userId);

        $key = "round:{$roundId}:scores:{$userId}";
        Redis::incrbyfloat($key, $score);
        Redis::expire($key, self::REDIS_TTL);

        // Mettre à jour le podium (sorted set)
        $podiumKey = "round:{$roundId}:podium";
        Redis::zincrby($podiumKey, $score, $userId);
        Redis::expire($podiumKey, self::REDIS_TTL);
    }

    /**
     * Récupère le score total d'un joueur pour un round
     */
    public function getUserScore(int $roundId, int $userId): float
    {
        $key = "round:{$roundId}:scores:{$userId}";
        $score = Redis::get($key);

        return $score ? (float) $score : 0.0;
    }

    /**
     * Récupère le podium (top N joueurs) pour un round
     *
     * @return array [userId => score, ...]
     */
    public function getPodium(int $roundId, int $limit = 100): array
    {
        $podiumKey = "round:{$roundId}:podium";
        $results = Redis::zrevrange($podiumKey, 0, $limit - 1, 'WITHSCORES');

        $podium = [];
        foreach ($results as $userId => $score) {
            $podium[(int) $userId] = (float) $score;
        }

        return $podium;
    }

    /**
     * Enregistre les détails d'une track écoutée par un joueur
     * Structure: {track_id, answer_id, response_time, position, score}
     *
     * @return bool true si la réponse a été ajoutée (nouvelle), false si elle existait déjà
     */
    public function recordTrackDetails(int $roundId, int $userId, int $trackId, ?float $responseTime = null, ?int $position = null, ?float $score = null, ?int $answerId = null): bool
    {
        $this->trackPlayer($roundId, $userId);

        // Utiliser un Set Redis pour stocker les réponses trouvées (atomique)
        // Format: round:{roundId}:answers:{userId}:{trackId} = Set d'answer_ids
        $answersKey = "round:{$roundId}:answers:{$userId}:{$trackId}";

        // Si answer_id est fourni, vérifier atomiquement s'il existe déjà
        if ($answerId !== null) {
            // Set par joueur (pour vérifier les doublons)
            $wasAdded = Redis::sadd($answersKey, $answerId);
            Redis::expire($answersKey, self::REDIS_TTL);

            // Si la réponse existait déjà (wasAdded = 0), ne pas continuer
            if ($wasAdded === 0) {
                return false;
            }

            // Set global par réponse (pour compter l'ordre de manière atomique)
            // Format: round:{roundId}:answer:{answerId}:players = Set de user_ids
            $globalAnswerKey = "round:{$roundId}:answer:{$answerId}:players";
            Redis::sadd($globalAnswerKey, $userId);
            Redis::expire($globalAnswerKey, self::REDIS_TTL);
        }

        // Stocker les détails complets dans un hash pour l'historique
        $key = "round:{$roundId}:tracks:{$userId}";

        // Récupérer l'historique existant
        $history = Redis::get($key);
        $tracks = $history ? json_decode($history, true) : [];

        // Chercher si cette combinaison track_id + answer_id existe déjà
        $existingIndex = false;
        if ($answerId !== null) {
            foreach ($tracks as $index => $trackData) {
                if (isset($trackData['track_id']) && $trackData['track_id'] == $trackId
                    && isset($trackData['answer_id']) && $trackData['answer_id'] == $answerId) {
                    $existingIndex = $index;
                    break;
                }
            }
        } else {
            // Si pas d'answer_id, chercher juste par track_id (pour compatibilité)
            $existingIndex = array_search($trackId, array_column($tracks, 'track_id'));
        }

        $trackData = [
            'track_id' => $trackId,
            'answer_id' => $answerId,
            'response_time' => $responseTime,
            'position' => $position,
            'score' => $score,
        ];

        if ($existingIndex !== false) {
            // Mettre à jour les données existantes (merge)
            $tracks[$existingIndex] = array_merge($tracks[$existingIndex], array_filter($trackData, fn ($v) => $v !== null));
        } else {
            // Ajouter une nouvelle entrée
            $tracks[] = $trackData;
        }

        // Sauvegarder dans Redis
        Redis::set($key, json_encode($tracks));
        Redis::expire($key, self::REDIS_TTL);

        return true;
    }

    /**
     * Récupère l'historique complet des tracks pour un joueur
     *
     * @return array [['track_id' => int, 'answer_id' => int, 'response_time' => float, 'position' => int, 'score' => float], ...]
     */
    public function getTracksHistory(int $roundId, int $userId): array
    {
        $key = "round:{$roundId}:tracks:{$userId}";
        $history = Redis::get($key);

        if ($history === null || $history === false || $history === '') {
            return [];
        }

        $decoded = json_decode($history, true);

        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Récupère les answer_ids déjà trouvés par un joueur pour une track spécifique
     *
     * @return array [answer_id, ...]
     */
    public function getFoundAnswerIds(int $roundId, int $userId, int $trackId): array
    {
        // Utiliser le Set Redis pour une vérification rapide et atomique
        $answersKey = "round:{$roundId}:answers:{$userId}:{$trackId}";
        $answerIds = Redis::smembers($answersKey);

        if (! empty($answerIds)) {
            return array_map('intval', $answerIds);
        }

        // Fallback vers l'historique JSON (pour compatibilité avec anciens rounds)
        $history = $this->getTracksHistory($roundId, $userId);
        $answerIds = [];

        foreach ($history as $trackData) {
            if ($trackData['track_id'] == $trackId && isset($trackData['answer_id'])) {
                $answerIds[] = $trackData['answer_id'];
            }
        }

        return array_unique($answerIds);
    }

    /**
     * Compte combien de joueurs ont trouvé une réponse spécifique
     * Utilisé pour calculer l'ordre (premier, deuxième, troisième)
     *
     * @param  int|null  $excludeUserId  Si fourni, exclut cet utilisateur du comptage
     * @return int Nombre de joueurs ayant trouvé cette réponse
     */
    public function countPlayersWhoFoundAnswer(int $roundId, int $trackId, int $answerId, ?int $excludeUserId = null): int
    {
        // Utiliser le Set global par réponse pour un comptage atomique et rapide
        // Format: round:{roundId}:answer:{answerId}:players = Set de user_ids
        $globalAnswerKey = "round:{$roundId}:answer:{$answerId}:players";
        $count = Redis::scard($globalAnswerKey);

        // Si l'utilisateur doit être exclu et qu'il est dans le Set, soustraire 1
        if ($excludeUserId !== null && $count > 0) {
            $isMember = Redis::sismember($globalAnswerKey, $excludeUserId);
            if ($isMember) {
                $count--;
            }
        }

        // Fallback vers Score DB si Redis est vide (compatibilité avec anciens rounds)
        if ($count === 0) {
            $query = Score::where('round_id', $roundId)
                ->where('track_id', $trackId)
                ->where('answer_id', $answerId);

            if ($excludeUserId !== null) {
                $query->where('user_id', '!=', $excludeUserId);
            }

            $count = $query->count();
        }

        return $count;
    }

    /**
     * Compte le nombre total de réponses trouvées par un joueur pour une track
     *
     * @return int Nombre de réponses trouvées
     */
    public function countFoundAnswersForUser(int $roundId, int $userId, int $trackId): int
    {
        $foundAnswers = $this->getFoundAnswerIds($roundId, $userId, $trackId);

        return count($foundAnswers);
    }

    /**
     * Récupère le nombre de tracks écoutées par un joueur
     */
    public function getTracksListenedCount(int $roundId, int $userId): int
    {
        return count($this->getTracksHistory($roundId, $userId));
    }

    /**
     * Récupère tous les scores pour un round
     *
     * @return array [userId => score, ...]
     */
    public function getAllScores(int $roundId): array
    {
        $scores = $this->getPodium($roundId, 10_000);

        if ($scores !== []) {
            return $scores;
        }

        // Legacy rounds may only have per-user score keys (avoid Redis KEYS in production).
        foreach ($this->playerIds($roundId) as $userId) {
            $score = Redis::get("round:{$roundId}:scores:{$userId}");
            if ($score !== null) {
                $scores[$userId] = (float) $score;
            }
        }

        return $scores;
    }

    /**
     * Récupère tous les historiques de tracks pour un round
     *
     * @return array [userId => [['track_id' => int, ...], ...], ...]
     */
    public function getAllTracksHistory(int $roundId): array
    {
        $histories = [];

        foreach ($this->playerIds($roundId) as $userId) {
            $history = $this->getTracksHistory($roundId, $userId);
            if ($history !== []) {
                $histories[$userId] = $history;
            }
        }

        return $histories;
    }

    /**
     * Récupère tous les counts de tracks écoutées pour un round
     *
     * @return array [userId => count, ...]
     */
    public function getAllTracksListenedCounts(int $roundId): array
    {
        $histories = $this->getAllTracksHistory($roundId);
        $counts = [];

        foreach ($histories as $userId => $history) {
            $counts[$userId] = count($history);
        }

        return $counts;
    }

    /**
     * Récupère les métriques de performance depuis Redis
     *
     * @return array ['score' => float, 'tracks_listened' => int, 'tracks_history' => array]
     */
    public function getUserMetrics(int $roundId, int $userId): array
    {
        return [
            'score' => $this->getUserScore($roundId, $userId),
            'tracks_listened' => $this->getTracksListenedCount($roundId, $userId),
            'tracks_history' => $this->getTracksHistory($roundId, $userId),
        ];
    }

    /**
     * Nettoie toutes les clés Redis pour un round
     */
    public function cleanup(int $roundId): void
    {
        $keys = [
            "round:{$roundId}:podium",
            $this->playersKey($roundId),
        ];

        foreach ($this->playerIds($roundId) as $userId) {
            $keys[] = "round:{$roundId}:scores:{$userId}";
            $keys[] = "round:{$roundId}:tracks:{$userId}";
        }

        $keys = array_values(array_unique($keys));

        if ($keys !== []) {
            Redis::del($keys);
        }
    }
}
