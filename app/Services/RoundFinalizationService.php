<?php

namespace App\Services;

use App\Jobs\UpdateUserLevel;
use App\Models\Round;
use App\Models\Team;
use App\Models\TotalScore;
use App\Models\User;
use App\Services\DTO\RoundFinalizationResult;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoundFinalizationService
{
    public function __construct(
        private RoundScoreService $roundScoreService,
        private EloService $eloService,
    ) {}

    public function finalize(Round $round): RoundFinalizationResult
    {
        $round->refresh();
        $round->load('room');

        if (! $round->finished_at) {
            Log::warning('RoundFinalization: Round not finished', ['round_id' => $round->id]);

            return new RoundFinalizationResult(processed: false, skipReason: 'not_finished');
        }

        if ($round->standings()->exists()) {
            Log::info('RoundFinalization: Standings already exist', ['round_id' => $round->id]);

            return new RoundFinalizationResult(processed: false, skipReason: 'already_finalized');
        }

        $eloUpdates = [];
        $finalized = false;

        DB::transaction(function () use ($round, &$eloUpdates, &$finalized) {
            $lock = DB::table('rounds')
                ->where('id', $round->id)
                ->lockForUpdate()
                ->first();

            if (! $lock) {
                Log::warning('RoundFinalization: Round not found', ['round_id' => $round->id]);

                return;
            }

            if ($round->standings()->exists()) {
                return;
            }

            $snapshot = $this->snapshotScores($round);

            if ($snapshot['scores'] === []) {
                Log::info('RoundFinalization: No scores to finalize', ['round_id' => $round->id]);

                return;
            }

            $scores = $snapshot['scores'];
            $tracksHistory = $snapshot['tracks_history'];
            $hadRedisScores = $snapshot['source'] === 'redis';

            $users = User::query()
                ->whereIn('id', array_keys($scores))
                ->with('team')
                ->get()
                ->keyBy('id');

            $podium = $this->buildPodium($scores, $users);
            $this->calculateTrackPositions($tracksHistory, $scores);

            $result = $this->eloService->updateElosForRound($round, $podium, $tracksHistory);
            $standings = $result['standings'];
            $eloUpdates = $result['elo_updates'];

            if ($standings === []) {
                Log::warning('RoundFinalization: No standings created', ['round_id' => $round->id]);

                return;
            }

            $this->assertStandingsHaveTotals($standings, $round->id);

            $this->updateTotalScores($round, $scores, $users);

            if ($hadRedisScores) {
                $this->roundScoreService->cleanup($round->id);
            }

            if ($snapshot['source'] === 'database' || $round->scores()->exists()) {
                $deleted = $round->scores()->delete();
                Log::info('RoundFinalization: Cleaned up legacy DB scores', [
                    'round_id' => $round->id,
                    'deleted_scores_count' => $deleted,
                ]);
            }

            $finalized = true;
        });

        return new RoundFinalizationResult(
            processed: $finalized,
            eloUpdates: $eloUpdates,
            skipReason: $finalized ? null : 'no_scores',
        );
    }

    /**
     * @return array{
     *     scores: array<int, float>,
     *     tracks_history: array<int, list<array<string, mixed>>>,
     *     source: 'redis'|'database'
     * }
     */
    private function snapshotScores(Round $round): array
    {
        $scores = $this->roundScoreService->getAllScores($round->id);
        $tracksHistory = $this->roundScoreService->getAllTracksHistory($round->id);

        if ($scores !== []) {
            return [
                'scores' => $scores,
                'tracks_history' => $tracksHistory,
                'source' => 'redis',
            ];
        }

        return [
            'scores' => $this->scoresFromDatabase($round),
            'tracks_history' => $this->tracksHistoryFromDatabase($round),
            'source' => 'database',
        ];
    }

    /**
     * @param  array<int, float>  $scores
     * @param  Collection<int, User>  $users
     */
    private function buildPodium(array $scores, Collection $users): Collection
    {
        $podiumData = [];

        foreach ($scores as $userId => $total) {
            $user = $users->get($userId);
            $podiumData[] = (object) [
                'user_id' => (int) $userId,
                'total' => $total,
                'team_id' => $user?->team?->id,
            ];
        }

        usort($podiumData, fn ($a, $b) => $b->total <=> $a->total);

        return collect($podiumData);
    }

    /**
     * @param  array<int, list<array<string, mixed>>>  $tracksHistory
     * @param  array<int, float>  $scores
     */
    private function calculateTrackPositions(array &$tracksHistory, array $scores): void
    {
        if ($tracksHistory === []) {
            return;
        }

        $tracksByTrackId = [];

        foreach ($tracksHistory as $userId => $history) {
            foreach ($history as $trackData) {
                $trackId = $trackData['track_id'];
                $tracksByTrackId[$trackId][] = [
                    'user_id' => $userId,
                    'score' => $trackData['score'] ?? 0,
                    'response_time' => $trackData['response_time'] ?? null,
                ];
            }
        }

        foreach ($tracksByTrackId as $trackId => $players) {
            usort($players, function ($a, $b) {
                if ($a['score'] != $b['score']) {
                    return $b['score'] <=> $a['score'];
                }

                if ($a['response_time'] !== null && $b['response_time'] !== null) {
                    return $a['response_time'] <=> $b['response_time'];
                }

                return 0;
            });

            foreach ($players as $position => $player) {
                $userId = $player['user_id'];

                if (! isset($tracksHistory[$userId])) {
                    continue;
                }

                foreach ($tracksHistory[$userId] as &$trackData) {
                    if ($trackData['track_id'] == $trackId) {
                        $trackData['position'] = $position + 1;
                    }
                }
            }
        }
    }

    /**
     * @param  list<array<string, mixed>>  $standings
     */
    private function assertStandingsHaveTotals(array $standings, int $roundId): void
    {
        $missingTotals = collect($standings)->filter(
            fn ($standing) => ! isset($standing['total_score']) || $standing['total_score'] === null
        );

        if ($missingTotals->isNotEmpty()) {
            throw new \RuntimeException("Round {$roundId}: standings missing total_score");
        }
    }

    /**
     * @param  array<int, float>  $scores
     * @param  Collection<int, User>  $users
     */
    private function updateTotalScores(Round $round, array $scores, Collection $users): void
    {
        $room = $round->room;

        if (! $room) {
            return;
        }

        foreach ($scores as $userId => $score) {
            $user = $users->get($userId);

            if (! $user) {
                continue;
            }

            TotalScore::updateOrCreate(
                [
                    'totalscorable_type' => User::class,
                    'totalscorable_id' => $userId,
                    'room_id' => $room->id,
                ],
                []
            )->increment('score', $score);

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

            if ($room->is_public) {
                UpdateUserLevel::dispatch(user: $user, type: 'score');
            }
        }
    }

    /**
     * @return array<int, float>
     */
    private function scoresFromDatabase(Round $round): array
    {
        return $round->scores()
            ->selectRaw('user_id, SUM(score) as total')
            ->groupBy('user_id')
            ->pluck('total', 'user_id')
            ->map(fn ($total) => (float) $total)
            ->all();
    }

    /**
     * @return array<int, list<array<string, mixed>>>
     */
    private function tracksHistoryFromDatabase(Round $round): array
    {
        $history = [];

        foreach ($round->scores()->get()->groupBy('user_id') as $userId => $userScores) {
            $history[(int) $userId] = $userScores->map(fn ($score) => [
                'track_id' => $score->track_id,
                'answer_id' => $score->answer_id,
                'response_time' => $score->time,
                'position' => null,
                'score' => $score->score,
            ])->values()->all();
        }

        return $history;
    }
}
