<?php

namespace App\Services;

use App\Models\Room;
use App\Models\RoundStanding;
use App\Models\TrackAnswer;

class RoundPerformanceMetricsService
{
    /**
     * @param  list<int>  $trackIds
     * @return array<int, int>
     */
    public function requiredAnswersPerTrack(array $trackIds): array
    {
        if ($trackIds === []) {
            return [];
        }

        return TrackAnswer::query()
            ->whereIn('track_id', $trackIds)
            ->selectRaw('track_id, COUNT(*) as answers_count')
            ->groupBy('track_id')
            ->pluck('answers_count', 'track_id')
            ->map(fn ($count) => (int) $count)
            ->all();
    }

    public function speedBonusThresholdForRoom(?Room $room): float
    {
        $trackDuration = $room?->track_duration ?? 30;

        return $trackDuration * 0.18;
    }

    /**
     * @return list<int>
     */
    public function trackIdsFromRound(mixed $tracks): array
    {
        if (! is_array($tracks)) {
            $tracks = array_values((array) $tracks);
        }

        return collect($tracks)
            ->map(fn ($trackId) => (int) $trackId)
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    /**
     * @return array{
     *     average_response_time: float|null,
     *     fast_answers_count: int,
     *     total_answers_count: int
     * }
     */
    public function forStanding(RoundStanding $standing): array
    {
        $tracksHistory = $standing->tracks_history;

        if ($tracksHistory === []) {
            return $this->emptyMetrics();
        }

        $standing->loadMissing('round.room');
        $round = $standing->round;

        if ($round === null) {
            return $this->emptyMetrics();
        }

        return $this->fromTracksHistory(
            $tracksHistory,
            $this->requiredAnswersPerTrack($this->trackIdsFromRound($round->tracks)),
            $this->speedBonusThresholdForRoom($round->room),
        );
    }

    /**
     * Calcule les métriques depuis l'historique des extraits joués.
     * Le temps moyen correspond au temps de complétion d'un extrait (dernière réponse trouvée).
     *
     * @param  list<array{track_id?: int, answer_id?: int|null, response_time?: float|null, score?: float|null}>  $userTracksHistory
     * @param  array<int, int>  $requiredAnswersPerTrack
     * @return array{
     *     average_response_time: float|null,
     *     fast_answers_count: int,
     *     total_answers_count: int
     * }
     */
    public function fromTracksHistory(
        array $userTracksHistory,
        array $requiredAnswersPerTrack,
        float $speedBonusThreshold,
    ): array {
        if ($userTracksHistory === []) {
            return $this->emptyMetrics();
        }

        $answersByTrack = [];

        foreach ($userTracksHistory as $entry) {
            $trackId = (int) ($entry['track_id'] ?? 0);

            if ($trackId === 0) {
                continue;
            }

            if (! isset($answersByTrack[$trackId])) {
                $answersByTrack[$trackId] = [
                    'answer_ids' => [],
                    'max_response_time' => null,
                ];
            }

            if (isset($entry['answer_id'])) {
                $answersByTrack[$trackId]['answer_ids'][(int) $entry['answer_id']] = true;
            }

            $responseTime = $entry['response_time'] ?? null;

            if ($responseTime !== null) {
                $currentMax = $answersByTrack[$trackId]['max_response_time'];
                $answersByTrack[$trackId]['max_response_time'] = $currentMax === null
                    ? (float) $responseTime
                    : max($currentMax, (float) $responseTime);
            }
        }

        $completionTimes = [];
        $totalAnswersCount = 0;

        foreach ($answersByTrack as $trackId => $data) {
            $foundCount = count($data['answer_ids']);
            $totalAnswersCount += $foundCount;
            $required = $requiredAnswersPerTrack[$trackId] ?? 0;

            if ($required === 0 || $foundCount < $required || $data['max_response_time'] === null) {
                continue;
            }

            $completionTimes[] = $data['max_response_time'];
        }

        $averageResponseTime = $completionTimes !== []
            ? array_sum($completionTimes) / count($completionTimes)
            : null;

        $fastAnswersCount = count(array_filter(
            $completionTimes,
            fn (float $time) => $time < $speedBonusThreshold,
        ));

        return [
            'average_response_time' => $averageResponseTime !== null ? round($averageResponseTime, 3) : null,
            'fast_answers_count' => $fastAnswersCount,
            'total_answers_count' => $totalAnswersCount,
        ];
    }

    /**
     * @return array{
     *     average_response_time: null,
     *     fast_answers_count: 0,
     *     total_answers_count: 0
     * }
     */
    private function emptyMetrics(): array
    {
        return [
            'average_response_time' => null,
            'fast_answers_count' => 0,
            'total_answers_count' => 0,
        ];
    }
}
