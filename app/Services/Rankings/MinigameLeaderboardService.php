<?php

namespace App\Services\Rankings;

use App\Models\MinigameScore;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MinigameLeaderboardService
{
    public const int PER_PAGE = 50;

    private const int CACHE_TTL_MINUTES = 10;

    /**
     * @return array<string, mixed>
     */
    public function paginatedPayload(int $page = 1): array
    {
        $page = max(1, $page);

        return Cache::remember(
            "minigame_leaderboard:v1:page:{$page}",
            now()->addMinutes(self::CACHE_TTL_MINUTES),
            fn () => $this->serializePaginator($this->buildPaginator($page)),
        );
    }

    /**
     * @return array{position: int|null, score: int}
     */
    public function userContext(User $user): array
    {
        if ($user->isGuest()) {
            return ['position' => null, 'score' => 0];
        }

        return Cache::remember(
            "minigame_leaderboard:v1:user:{$user->id}:context",
            now()->addMinutes(self::CACHE_TTL_MINUTES),
            function () use ($user) {
                $score = (int) MinigameScore::query()
                    ->where('user_id', $user->id)
                    ->sum('score');

                if ($score <= 0) {
                    return ['position' => null, 'score' => 0];
                }

                $position = (int) DB::query()
                    ->fromSub(
                        MinigameScore::query()
                            ->selectRaw('user_id, sum(score) as total_score')
                            ->groupBy('user_id'),
                        'minigame_totals',
                    )
                    ->where('total_score', '>', $score)
                    ->count() + 1;

                return [
                    'position' => $position,
                    'score' => $score,
                ];
            },
        );
    }

    private function buildPaginator(int $page): LengthAwarePaginator
    {
        $paginated = MinigameScore::query()
            ->selectRaw('user_id, sum(score) as total_score')
            ->groupBy('user_id')
            ->orderByDesc('total_score')
            ->paginate(self::PER_PAGE, ['*'], 'page', $page);

        $users = User::with('userLevel')
            ->whereIn('id', $paginated->pluck('user_id'))
            ->get()
            ->keyBy('id');

        $mapped = $paginated->getCollection()->map(function ($row) use ($users) {
            $user = $users->get($row->user_id);

            if ($user === null) {
                return null;
            }

            return [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'photo' => $user->photo,
                    'elo' => $user->elo ?? 1500,
                    'userLevel' => $user->userLevel,
                ],
                'total_score' => (int) $row->total_score,
            ];
        })->filter()->values();

        return new Paginator(
            $mapped,
            $paginated->total(),
            $paginated->perPage(),
            $paginated->currentPage(),
            ['path' => Paginator::resolveCurrentPath(), 'query' => request()->query()],
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function serializePaginator(LengthAwarePaginator $paginator): array
    {
        return [
            'data' => $paginator->items(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'links' => $paginator->linkCollection()->toArray(),
        ];
    }
}
