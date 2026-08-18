<?php

namespace App\Services\Rooms;

use App\Models\Room;
use App\Models\RoundStanding;
use App\Models\Team;
use App\Models\TotalScore;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class RoomLeaderboardService
{
    private const int LIMIT = 50;

    private const int CACHE_TTL_MINUTES = 10;

    /**
     * @return array{
     *     lifetime: list<array<string, mixed>>,
     *     week: list<array<string, mixed>>,
     *     teams: list<array<string, mixed>>
     * }
     */
    public function leaderboardsForRoom(Room $room): array
    {
        return Cache::remember(
            $this->cacheKey($room),
            now()->addMinutes(self::CACHE_TTL_MINUTES),
            fn () => [
                'lifetime' => $this->buildUserLeaderboard($room, null),
                'week' => $this->buildUserLeaderboard($room, now()->subDays(7)),
                'teams' => $this->buildTeamLeaderboard($room),
            ],
        );
    }

    /**
     * @param  array{
     *     lifetime: list<array<string, mixed>>,
     *     week: list<array<string, mixed>>,
     *     teams: list<array<string, mixed>>
     * }  $leaderboards
     * @return array<string, mixed|null>
     */
    public function userSnapshot(Room $room, User $user, array $leaderboards): array
    {
        return [
            'week' => $this->resolveUserPeriodScore($leaderboards['week'], $user->id, function () use ($room, $user): ?array {
                $total = RoundStanding::query()
                    ->where('room_id', $room->id)
                    ->where('user_id', $user->id)
                    ->where('created_at', '>=', now()->subDays(7))
                    ->sum('total_score');

                if ((float) $total <= 0) {
                    return null;
                }

                return [
                    'user_id' => $user->id,
                    'total' => round((float) $total, 1),
                ];
            }),
            'lifetime' => $this->resolveUserPeriodScore($leaderboards['lifetime'], $user->id, function () use ($room, $user): ?array {
                $total = TotalScore::query()
                    ->byUsers()
                    ->where('room_id', $room->id)
                    ->where('totalscorable_id', $user->id)
                    ->sum('score');

                if ((float) $total <= 0) {
                    return null;
                }

                return [
                    'user_id' => $user->id,
                    'total' => round((float) $total, 1),
                ];
            }),
            'team' => $user->team_id
                ? $this->resolveTeamScore($room, $user->team_id, $leaderboards['teams'])
                : null,
        ];
    }

    public function forgetCache(Room $room): void
    {
        Cache::forget($this->cacheKey($room));
    }

    private function cacheKey(Room $room): string
    {
        return "room:{$room->id}:leaderboard:v4";
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function buildUserLeaderboard(Room $room, ?CarbonInterface $since): array
    {
        $totals = $since === null
            ? $this->lifetimeTotals($room)
            : $this->weekTotals($room, $since);

        if ($totals->isEmpty()) {
            return [];
        }

        $userIds = $totals->pluck('user_id')->map(fn ($id) => (int) $id)->all();

        $users = User::query()
            ->with('userLevel')
            ->whereIn('id', $userIds)
            ->get()
            ->keyBy('id');

        $standingStats = $this->standingStatsForUsers($room, $userIds, $since);

        return $totals->values()->map(function ($row, int $index) use ($users, $standingStats): array {
            $userId = (int) $row->user_id;
            $user = $users->get($userId);
            $stats = $standingStats->get($userId);

            return [
                'rank' => $index + 1,
                'user_id' => $userId,
                'total' => (float) $row->total,
                'user' => $user ? $this->formatUser($user) : null,
                'stats' => [
                    'level' => $user?->userLevel?->level ?? 1,
                    'elo' => $user?->elo ?? 1500,
                    'score' => (float) $row->total,
                    'rounds_played' => (int) ($stats->rounds_played ?? 0),
                    'avg_score_per_round' => isset($stats->avg_score_per_round) ? (float) $stats->avg_score_per_round : null,
                    'avg_response_time' => isset($stats->avg_response_time) ? (float) $stats->avg_response_time : null,
                    'best_round_score' => isset($stats->best_round_score) ? (float) $stats->best_round_score : null,
                    'best_win_streak' => isset($stats->best_win_streak) ? (int) $stats->best_win_streak : null,
                ],
            ];
        })->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function buildTeamLeaderboard(Room $room): array
    {
        $totals = TotalScore::query()
            ->byTeams()
            ->where('room_id', $room->id)
            ->selectRaw('totalscorable_id as team_id, ROUND(SUM(score), 1) as total')
            ->groupBy('totalscorable_id')
            ->orderByDesc('total')
            ->limit(self::LIMIT)
            ->get();

        if ($totals->isEmpty()) {
            return [];
        }

        $teams = Team::query()
            ->whereIn('id', $totals->pluck('team_id'))
            ->get()
            ->keyBy('id');

        return $totals->values()->map(function ($row, int $index) use ($teams): array {
            $team = $teams->get($row->team_id);

            return [
                'rank' => $index + 1,
                'team_id' => (int) $row->team_id,
                'total' => (float) $row->total,
                'team' => $team ? [
                    'id' => $team->id,
                    'name' => $team->name,
                    'photo' => $team->photo,
                ] : null,
            ];
        })->all();
    }

    private function lifetimeTotals(Room $room): Collection
    {
        return TotalScore::query()
            ->byUsers()
            ->where('room_id', $room->id)
            ->selectRaw('totalscorable_id as user_id, ROUND(SUM(score), 1) as total')
            ->groupBy('totalscorable_id')
            ->orderByDesc('total')
            ->limit(self::LIMIT)
            ->get();
    }

    private function weekTotals(Room $room, CarbonInterface $since): Collection
    {
        return RoundStanding::query()
            ->where('room_id', $room->id)
            ->where('created_at', '>=', $since)
            ->selectRaw('user_id, ROUND(SUM(total_score), 1) as total')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit(self::LIMIT)
            ->get();
    }

    /**
     * @param  list<int>  $userIds
     */
    private function standingStatsForUsers(Room $room, array $userIds, ?CarbonInterface $since): Collection
    {
        if ($userIds === []) {
            return collect();
        }

        return RoundStanding::query()
            ->where('room_id', $room->id)
            ->whereIn('user_id', $userIds)
            ->when($since !== null, fn ($query) => $query->where('created_at', '>=', $since))
            ->selectRaw('
                user_id,
                COUNT(*) as rounds_played,
                ROUND(AVG(total_score), 1) as avg_score_per_round,
                ROUND(AVG(average_response_time), 2) as avg_response_time,
                ROUND(MAX(total_score), 1) as best_round_score,
                MAX(win_streak) as best_win_streak
            ')
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');
    }

    /**
     * @return array<string, mixed>
     */
    private function formatUser(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'photo' => $user->photo,
            'elo' => $user->elo ?? 1500,
            'is_guest' => (bool) $user->is_guest,
            'user_level' => $user->userLevel ? [
                'level' => $user->userLevel->level,
                'total_xp' => $user->userLevel->total_xp,
                'current_xp' => $user->userLevel->current_xp,
                'xp_for_next_level' => $user->userLevel->xp_for_next_level,
            ] : null,
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $leaderboard
     * @return array<string, mixed>|null
     */
    private function resolveUserPeriodScore(array $leaderboard, int $userId, callable $fallback): ?array
    {
        foreach ($leaderboard as $entry) {
            if ((int) ($entry['user_id'] ?? 0) === $userId) {
                return [
                    'user_id' => $userId,
                    'total' => (float) ($entry['total'] ?? 0),
                    'rank' => (int) ($entry['rank'] ?? 0),
                ];
            }
        }

        return $fallback();
    }

    /**
     * @param  list<array<string, mixed>>  $teamLeaderboard
     * @return array<string, mixed>|null
     */
    private function resolveTeamScore(Room $room, int $teamId, array $teamLeaderboard): ?array
    {
        foreach ($teamLeaderboard as $entry) {
            if ((int) ($entry['team_id'] ?? 0) === $teamId) {
                return [
                    'team_id' => $teamId,
                    'total' => (float) ($entry['total'] ?? 0),
                    'rank' => (int) ($entry['rank'] ?? 0),
                ];
            }
        }

        $total = TotalScore::query()
            ->byTeams()
            ->where('room_id', $room->id)
            ->where('totalscorable_id', $teamId)
            ->sum('score');

        if ((float) $total <= 0) {
            return null;
        }

        return [
            'team_id' => $teamId,
            'total' => round((float) $total, 1),
        ];
    }
}
