<?php

namespace App\Services\Rankings;

use App\Models\Room;
use App\Models\RoundStanding;
use App\Models\TotalScore;
use App\Models\User;
use App\Models\UserLevel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class GlobalLeaderboardService
{
    public const int PER_PAGE = 10;

    /** @var list<string> */
    public const array SORTS = [
        'level',
        'score',
        'elo',
        'week',
        'avg_time',
        'best_round',
    ];

    private const int CACHE_TTL_MINUTES = 10;

    private ?int $roomId = null;

    /**
     * @return array<int, array{id: int, name: string}>
     */
    public function officialRooms(): array
    {
        return Cache::remember('global_leaderboard:official_rooms:v1', now()->addHour(), function () {
            return Room::query()
                ->isPublic()
                ->orderBy('name')
                ->get(['id', 'name'])
                ->map(fn (Room $room) => [
                    'id' => $room->id,
                    'name' => $room->name,
                ])
                ->all();
        });
    }

    public function resolveOfficialRoomId(?int $roomId): ?int
    {
        if ($roomId === null || $roomId <= 0) {
            return null;
        }

        return Room::query()->isPublic()->whereKey($roomId)->value('id');
    }

    /**
     * @return array<int, array{id: string, label: string}>
     */
    public function availableSorts(): array
    {
        return [
            ['id' => 'level', 'label' => __('Level')],
            ['id' => 'score', 'label' => __('Score')],
            ['id' => 'elo', 'label' => __('ELO')],
            ['id' => 'week', 'label' => __('Top Week')],
            ['id' => 'avg_time', 'label' => __('Avg. response time')],
            ['id' => 'best_round', 'label' => __('Best round')],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function paginatedPayload(string $sort, int $page = 1, ?int $roomId = null): array
    {
        return $this->executeWithRoom($roomId, function () use ($sort, $page) {
            $sort = $this->normalizeSort($sort);
            $page = max(1, $page);
            $cacheRoom = $this->roomId ?? 'all';

            return Cache::remember(
                "global_leaderboard:v3:{$sort}:room:{$cacheRoom}:page:{$page}",
                now()->addMinutes(self::CACHE_TTL_MINUTES),
                function () use ($sort, $page) {
                    $paginator = $this->buildPaginator($sort, $page);

                    return $this->serializePaginator($paginator);
                },
            );
        });
    }

    /**
     * @return array{position: int|null, entry: array<string, mixed>|null}
     */
    public function userContext(User $user, string $sort, ?int $roomId = null): array
    {
        return $this->executeWithRoom($roomId, function () use ($user, $sort) {
            $sort = $this->normalizeSort($sort);
            $cacheRoom = $this->roomId ?? 'all';

            return Cache::remember(
                "global_leaderboard:v3:user:{$user->id}:sort:{$sort}:room:{$cacheRoom}:context",
                now()->addMinutes(self::CACHE_TTL_MINUTES),
                fn () => [
                    'position' => $this->calculateUserPosition($user, $sort),
                    'entry' => $this->userEntry($user),
                ],
            );
        });
    }

    /**
     * @return array<string, mixed>|null
     */
    public function userEntry(User $user): ?array
    {
        $user->loadMissing('userLevel');
        $stats = $this->loadStatsForUsers([$user->id]);

        return $this->formatEntry($user, $stats);
    }

    /**
     * @template T
     *
     * @param  callable(): T  $callback
     * @return T
     */
    private function executeWithRoom(?int $roomId, callable $callback): mixed
    {
        $previousRoomId = $this->roomId;
        $this->roomId = $this->resolveOfficialRoomId($roomId);

        try {
            return $callback();
        } finally {
            $this->roomId = $previousRoomId;
        }
    }

    private function normalizeSort(string $sort): string
    {
        return in_array($sort, self::SORTS, true) ? $sort : 'elo';
    }

    private function buildPaginator(string $sort, int $page): LengthAwarePaginator
    {
        return match ($sort) {
            'level' => $this->paginateByLevel($page),
            'score' => $this->paginateByScore($page),
            'elo' => $this->paginateByElo($page),
            'week' => $this->paginateByWeek($page),
            'avg_time' => $this->paginateByAvgTime($page),
            'best_round' => $this->paginateByBestRound($page),
        };
    }

    private function paginateByLevel(int $page): LengthAwarePaginator
    {
        $paginator = $this->userLevelBaseQuery()
            ->orderByDesc('user_levels.level')
            ->orderByDesc('user_levels.total_xp')
            ->paginate(self::PER_PAGE, ['user_levels.*'], 'page', $page);

        return $this->mapUserLevelPaginator($paginator);
    }

    private function paginateByScore(int $page): LengthAwarePaginator
    {
        if ($this->roomId !== null) {
            $paginator = $this->publicUserScoresQuery()
                ->select('total_scores.totalscorable_id as user_id')
                ->selectRaw('ROUND(SUM(total_scores.score), 1) as sort_value')
                ->groupBy('total_scores.totalscorable_id')
                ->orderByDesc('sort_value')
                ->paginate(self::PER_PAGE, ['*'], 'page', $page);

            return $this->mapStandingPaginator($paginator);
        }

        $paginator = $this->userLevelBaseQuery()
            ->orderByDesc('user_levels.score_public_rooms')
            ->orderByDesc('user_levels.total_xp')
            ->paginate(self::PER_PAGE, ['user_levels.*'], 'page', $page);

        return $this->mapUserLevelPaginator($paginator);
    }

    private function paginateByBestRound(int $page): LengthAwarePaginator
    {
        if ($this->roomId !== null) {
            $paginator = $this->publicRoundStandingsQuery()
                ->selectRaw('round_standings.user_id, ROUND(MAX(round_standings.total_score), 1) as sort_value')
                ->groupBy('round_standings.user_id')
                ->orderByDesc('sort_value')
                ->paginate(self::PER_PAGE, ['*'], 'page', $page);

            return $this->mapStandingPaginator($paginator);
        }

        $paginator = $this->userLevelBaseQuery()
            ->orderByDesc('user_levels.best_round_score')
            ->orderByDesc('user_levels.score_public_rooms')
            ->paginate(self::PER_PAGE, ['user_levels.*'], 'page', $page);

        return $this->mapUserLevelPaginator($paginator);
    }

    private function paginateByElo(int $page): LengthAwarePaginator
    {
        $query = User::query()
            ->whereNotNull('elo');

        if ($this->roomId !== null) {
            $query->whereIn('id', $this->usersInScopedRoomSubquery());
        }

        $paginator = $query
            ->orderByDesc('elo')
            ->orderBy('id')
            ->paginate(self::PER_PAGE, ['id'], 'page', $page);

        $userIds = $paginator->getCollection()->pluck('id')->map(fn ($id) => (int) $id)->all();

        return $this->mapUserPaginator($paginator, $userIds);
    }

    private function paginateByWeek(int $page): LengthAwarePaginator
    {
        $paginator = $this->publicRoundStandingsQuery()
            ->where('round_standings.created_at', '>=', now()->subDays(7))
            ->selectRaw('round_standings.user_id, ROUND(SUM(round_standings.total_score), 1) as sort_value')
            ->groupBy('round_standings.user_id')
            ->orderByDesc('sort_value')
            ->paginate(self::PER_PAGE, ['*'], 'page', $page);

        return $this->mapStandingPaginator($paginator);
    }

    private function paginateByAvgTime(int $page): LengthAwarePaginator
    {
        $paginator = $this->publicRoundStandingsQuery()
            ->whereNotNull('round_standings.average_response_time')
            ->selectRaw('round_standings.user_id, ROUND(AVG(round_standings.average_response_time), 3) as sort_value')
            ->groupBy('round_standings.user_id')
            ->orderBy('sort_value')
            ->paginate(self::PER_PAGE, ['*'], 'page', $page);

        return $this->mapStandingPaginator($paginator);
    }

    private function mapUserLevelPaginator(LengthAwarePaginator $paginator): LengthAwarePaginator
    {
        $userIds = $paginator->getCollection()->pluck('user_id')->map(fn ($id) => (int) $id)->all();

        return $this->mapUserPaginator($paginator, $userIds);
    }

    /**
     * @param  list<int>  $userIds
     */
    private function mapUserPaginator(LengthAwarePaginator $paginator, array $userIds): LengthAwarePaginator
    {
        $users = $this->loadUsers($userIds);
        $stats = $this->loadStatsForUsers($userIds);

        $items = collect($userIds)->map(function (int $userId) use ($users, $stats) {
            $user = $users->get($userId);

            if (! $user) {
                return null;
            }

            return $this->formatEntry($user, $stats);
        })->filter()->values();

        return $this->replacePaginatorItems($paginator, $items);
    }

    private function mapStandingPaginator(LengthAwarePaginator $paginator): LengthAwarePaginator
    {
        $userIds = $paginator->getCollection()->pluck('user_id')->map(fn ($id) => (int) $id)->all();

        return $this->mapUserPaginator($paginator, $userIds);
    }

    private function replacePaginatorItems(LengthAwarePaginator $paginator, Collection $items): LengthAwarePaginator
    {
        return new Paginator(
            $items,
            $paginator->total(),
            $paginator->perPage(),
            $paginator->currentPage(),
            ['path' => request()->url(), 'query' => request()->query()],
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function serializePaginator(LengthAwarePaginator $paginator): array
    {
        return [
            'data' => $paginator->items(),
            'total' => $paginator->total(),
            'per_page' => $paginator->perPage(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'links' => $paginator->linkCollection()->toArray(),
        ];
    }

    private function userLevelBaseQuery()
    {
        $query = UserLevel::query()
            ->join('users', 'users.id', '=', 'user_levels.user_id')
            ->whereNull('users.deleted_at');

        if ($this->roomId !== null) {
            $query->whereIn('user_levels.user_id', $this->usersInScopedRoomSubquery());
        }

        return $query;
    }

    /**
     * @return Builder
     */
    private function usersInScopedRoomSubquery()
    {
        return DB::table('round_standings')
            ->select('user_id')
            ->when(
                $this->roomId !== null,
                fn (Builder $query) => $query->where('room_id', $this->roomId),
                fn (Builder $query) => $query
                    ->join('rooms', 'rooms.id', '=', 'round_standings.room_id')
                    ->where('rooms.is_public', true),
            )
            ->distinct();
    }

    /**
     * @param  list<int>  $userIds
     * @return Collection<int, User>
     */
    private function loadUsers(array $userIds): Collection
    {
        if ($userIds === []) {
            return collect();
        }

        return User::query()
            ->with('userLevel')
            ->whereIn('id', $userIds)
            ->get()
            ->keyBy('id');
    }

    /**
     * @param  list<int>  $userIds
     * @return array{round: Collection<int, object>, room_scores: Collection<int, object>}
     */
    private function loadStatsForUsers(array $userIds): array
    {
        if ($userIds === []) {
            return [
                'round' => collect(),
                'room_scores' => collect(),
            ];
        }

        $since = now()->subDays(7)->toDateTimeString();

        $round = $this->publicRoundStandingsQuery()
            ->whereIn('round_standings.user_id', $userIds)
            ->selectRaw('
                round_standings.user_id,
                ROUND(SUM(CASE WHEN round_standings.created_at >= ? THEN round_standings.total_score ELSE 0 END), 1) as week_score,
                COUNT(*) as rounds_played,
                ROUND(AVG(round_standings.average_response_time), 3) as avg_response_time,
                ROUND(MAX(round_standings.total_score), 1) as best_round_score,
                MAX(round_standings.win_streak) as best_win_streak
            ', [$since])
            ->groupBy('round_standings.user_id')
            ->get()
            ->keyBy('user_id');

        $roomScores = collect();

        if ($this->roomId !== null) {
            $roomScores = $this->publicUserScoresQuery()
                ->whereIn('total_scores.totalscorable_id', $userIds)
                ->selectRaw('total_scores.totalscorable_id as user_id, ROUND(SUM(total_scores.score), 1) as total_score')
                ->groupBy('total_scores.totalscorable_id')
                ->get()
                ->keyBy('user_id');
        }

        return [
            'round' => $round,
            'room_scores' => $roomScores,
        ];
    }

    /**
     * @param  array{round: Collection<int, object>, room_scores: Collection<int, object>}  $stats
     * @return array<string, mixed>
     */
    private function formatEntry(User $user, array $stats): array
    {
        $userLevel = $user->userLevel;
        $standing = $stats['round']->get($user->id);
        $roomScore = $stats['room_scores']->get($user->id);

        $score = $this->roomId !== null
            ? (float) ($roomScore->total_score ?? 0)
            : (float) ($userLevel?->score_public_rooms ?? 0);

        $bestRound = $this->roomId !== null
            ? (float) ($standing->best_round_score ?? 0)
            : (float) max(
                (float) ($standing->best_round_score ?? 0),
                (float) ($userLevel?->best_round_score ?? 0),
            );

        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'photo' => $user->photo,
                'elo' => $user->elo ?? 1500,
                'is_guest' => (bool) $user->is_guest,
                'userLevel' => $userLevel ? [
                    'level' => $userLevel->level,
                    'total_xp' => $userLevel->total_xp,
                    'current_xp' => $userLevel->current_xp,
                    'xp_for_next_level' => $userLevel->xp_for_next_level,
                ] : null,
            ],
            'stats' => [
                'level' => $userLevel?->level ?? 1,
                'total_xp' => $userLevel?->total_xp ?? 0,
                'elo' => $user->elo ?? 1500,
                'score' => $score,
                'week_score' => $standing ? (float) $standing->week_score : 0.0,
                'rounds_played' => (int) ($standing->rounds_played ?? $userLevel?->rounds_played_count ?? 0),
                'avg_response_time' => isset($standing->avg_response_time) ? (float) $standing->avg_response_time : null,
                'best_round_score' => $bestRound,
                'best_win_streak' => isset($standing->best_win_streak) ? (int) $standing->best_win_streak : null,
            ],
        ];
    }

    private function publicRoundStandingsQuery()
    {
        $query = RoundStanding::query();

        if ($this->roomId !== null) {
            return $query->where('round_standings.room_id', $this->roomId);
        }

        return $query
            ->join('rooms', 'round_standings.room_id', '=', 'rooms.id')
            ->where('rooms.is_public', true);
    }

    private function publicUserScoresQuery()
    {
        $query = TotalScore::query()
            ->where('total_scores.totalscorable_type', User::class);

        if ($this->roomId !== null) {
            return $query->where('total_scores.room_id', $this->roomId);
        }

        return $query
            ->join('rooms', 'rooms.id', '=', 'total_scores.room_id')
            ->where('rooms.is_public', true);
    }

    private function calculateUserPosition(User $user, string $sort): ?int
    {
        return match ($sort) {
            'level' => $this->userPositionByLevel($user),
            'score' => $this->userPositionByScore($user),
            'elo' => $this->userPositionByElo($user),
            'week' => $this->userPositionByWeek($user),
            'avg_time' => $this->userPositionByAvgTime($user),
            'best_round' => $this->userPositionByBestRound($user),
        };
    }

    private function userPositionByLevel(User $user): ?int
    {
        $user->loadMissing('userLevel');

        if (! $user->userLevel) {
            return null;
        }

        $query = $this->userLevelBaseQuery()
            ->where(function ($inner) use ($user) {
                $inner->where('user_levels.level', '>', $user->userLevel->level)
                    ->orWhere(function ($tieBreaker) use ($user) {
                        $tieBreaker->where('user_levels.level', '=', $user->userLevel->level)
                            ->where('user_levels.total_xp', '>', $user->userLevel->total_xp);
                    });
            });

        return $query->count() + 1;
    }

    private function userPositionByScore(User $user): ?int
    {
        if ($this->roomId !== null) {
            $userScore = (float) $this->publicUserScoresQuery()
                ->where('total_scores.totalscorable_id', $user->id)
                ->sum('total_scores.score');

            if ($userScore <= 0) {
                return null;
            }

            return (int) DB::query()->fromSub(
                $this->publicUserScoresQuery()
                    ->selectRaw('total_scores.totalscorable_id as user_id, ROUND(SUM(total_scores.score), 1) as total_score')
                    ->groupBy('total_scores.totalscorable_id')
                    ->havingRaw('ROUND(SUM(total_scores.score), 1) > ?', [$userScore]),
                'room_scores'
            )->count() + 1;
        }

        $user->loadMissing('userLevel');

        if (! $user->userLevel || (float) $user->userLevel->score_public_rooms <= 0) {
            return null;
        }

        return UserLevel::query()
            ->join('users', 'users.id', '=', 'user_levels.user_id')
            ->whereNull('users.deleted_at')
            ->where('user_levels.score_public_rooms', '>', $user->userLevel->score_public_rooms)
            ->count() + 1;
    }

    private function userPositionByElo(User $user): ?int
    {
        $elo = $user->elo ?? 1500;

        $query = User::query()
            ->whereNotNull('elo')
            ->where('elo', '>', $elo);

        if ($this->roomId !== null) {
            $query->whereIn('id', $this->usersInScopedRoomSubquery());
        }

        return $query->count() + 1;
    }

    private function userPositionByWeek(User $user): ?int
    {
        $userWeekScore = $this->publicRoundStandingsQuery()
            ->where('round_standings.user_id', $user->id)
            ->where('round_standings.created_at', '>=', now()->subDays(7))
            ->sum('round_standings.total_score');

        if (! $userWeekScore || (float) $userWeekScore <= 0) {
            return null;
        }

        return (int) DB::query()->fromSub(
            $this->publicRoundStandingsQuery()
                ->where('round_standings.created_at', '>=', now()->subDays(7))
                ->selectRaw('round_standings.user_id, ROUND(SUM(round_standings.total_score), 1) as total_score')
                ->groupBy('round_standings.user_id')
                ->havingRaw('ROUND(SUM(round_standings.total_score), 1) > ?', [$userWeekScore]),
            'weekly_scores'
        )->count() + 1;
    }

    private function userPositionByAvgTime(User $user): ?int
    {
        $userAvg = $this->publicRoundStandingsQuery()
            ->where('round_standings.user_id', $user->id)
            ->whereNotNull('round_standings.average_response_time')
            ->avg('round_standings.average_response_time');

        if ($userAvg === null) {
            return null;
        }

        return (int) DB::query()->fromSub(
            $this->publicRoundStandingsQuery()
                ->whereNotNull('round_standings.average_response_time')
                ->selectRaw('round_standings.user_id')
                ->groupBy('round_standings.user_id')
                ->havingRaw('AVG(round_standings.average_response_time) < ?', [$userAvg]),
            'avg_times'
        )->count() + 1;
    }

    private function userPositionByBestRound(User $user): ?int
    {
        if ($this->roomId !== null) {
            $bestRound = (float) $this->publicRoundStandingsQuery()
                ->where('round_standings.user_id', $user->id)
                ->max('round_standings.total_score');

            if ($bestRound <= 0) {
                return null;
            }

            return (int) DB::query()->fromSub(
                $this->publicRoundStandingsQuery()
                    ->selectRaw('round_standings.user_id')
                    ->groupBy('round_standings.user_id')
                    ->havingRaw('MAX(round_standings.total_score) > ?', [$bestRound]),
                'best_rounds'
            )->count() + 1;
        }

        $user->loadMissing('userLevel');

        $bestRound = max(
            (float) ($user->userLevel?->best_round_score ?? 0),
            (float) $this->publicRoundStandingsQuery()
                ->where('round_standings.user_id', $user->id)
                ->max('round_standings.total_score'),
        );

        if ($bestRound <= 0) {
            return null;
        }

        return UserLevel::query()
            ->join('users', 'users.id', '=', 'user_levels.user_id')
            ->whereNull('users.deleted_at')
            ->where('user_levels.best_round_score', '>', $bestRound)
            ->count() + 1;
    }
}
