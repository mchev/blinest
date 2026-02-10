<?php

namespace App\Services\Minigames;

use App\Jobs\UpdateUserLevel;
use App\Models\MinigameScore;
use App\Models\User;
use Illuminate\Support\Collection;

class MinigameScoreService
{
    public function record(User $user, string $gameType, int $score, array $metadata = []): MinigameScore
    {
        $minigameScore = MinigameScore::query()->create([
            'user_id' => $user->id,
            'game_type' => $gameType,
            'score' => $score,
            'metadata' => $metadata,
        ]);

        UpdateUserLevel::dispatch($user, 'score');

        return $minigameScore;
    }

    public function getTotalByUserAndType(User $user, string $gameType): int
    {
        return (int) MinigameScore::query()
            ->where('user_id', $user->id)
            ->where('game_type', $gameType)
            ->sum('score');
    }

    /**
     * @return array<string, int>
     */
    public function getTotalsByTypeForUser(User $user): array
    {
        $totals = MinigameScore::query()
            ->where('user_id', $user->id)
            ->selectRaw('game_type, sum(score) as total')
            ->groupBy('game_type')
            ->pluck('total', 'game_type');

        return [
            MinigameScore::TYPE_QUIZ => (int) ($totals->get(MinigameScore::TYPE_QUIZ, 0)),
            MinigameScore::TYPE_WHO_SANG => (int) ($totals->get(MinigameScore::TYPE_WHO_SANG, 0)),
            MinigameScore::TYPE_ALBUM_COVER => (int) ($totals->get(MinigameScore::TYPE_ALBUM_COVER, 0)),
        ];
    }

    /**
     * @return Collection<int, array{user_id: int, name: string, total: int}>
     */
    public function getLeaderboard(string $gameType, int $limit = 10): Collection
    {
        return MinigameScore::query()
            ->where('game_type', $gameType)
            ->selectRaw('user_id, sum(score) as total')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit($limit)
            ->with('user:id,name')
            ->get()
            ->map(fn (MinigameScore $row) => [
                'user_id' => $row->user_id,
                'name' => $row->user?->name ?? __('Unknown'),
                'total' => (int) $row->getAttribute('total'),
            ]);
    }
}
