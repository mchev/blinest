<?php

namespace App\Services\Profiles;

use App\Models\User;

class ProfileBadgeService
{
    /**
     * @return list<array{id: string, label_key: string, description_key: string, icon: string, earned: bool, current: int, target: int|null, progress_url: string|null}>
     */
    public function forUser(User $user, ?int $eloRank = null, bool $isSupporter = false): array
    {
        $user->loadMissing('userLevel');
        $userLevel = $user->userLevel;
        $metrics = $userLevel !== null ? [
            'rooms_created_count' => (int) ($userLevel->rooms_created_count ?? 0),
            'unique_rooms_played_count' => (int) ($userLevel->unique_rooms_played_count ?? 0),
            'consecutive_days_streak' => (int) ($userLevel->consecutive_days_streak ?? 0),
            'minigame_scores_total' => (int) ($userLevel->minigame_scores_total ?? 0),
            'rounds_played_count' => (int) ($userLevel->rounds_played_count ?? 0),
            'months_seniority' => (int) ($userLevel->months_seniority ?? 0),
            'tracks_liked_count' => (int) ($userLevel->tracks_liked_count ?? 0),
        ] : [
            'rooms_created_count' => 0,
            'unique_rooms_played_count' => 0,
            'consecutive_days_streak' => 0,
            'minigame_scores_total' => 0,
            'rounds_played_count' => 0,
            'months_seniority' => 0,
            'tracks_liked_count' => 0,
        ];

        $isSupporter = (bool) $isSupporter;

        $definitions = [
            [
                'id' => 'supporter',
                'label_key' => 'Profile badge supporter',
                'description_key' => 'Profile badge supporter desc',
                'icon' => 'crown',
                'earned' => $isSupporter,
                'current' => $isSupporter ? 1 : 0,
                'target' => 1,
                'progress_url' => route('docs.support'),
            ],
            [
                'id' => 'creator',
                'label_key' => 'Profile badge creator',
                'description_key' => 'Profile badge creator desc',
                'icon' => 'sparkles',
                'earned' => $metrics['rooms_created_count'] >= 1,
                'current' => $metrics['rooms_created_count'],
                'target' => 1,
                'progress_url' => route('rooms.create'),
            ],
            [
                'id' => 'explorer',
                'label_key' => 'Profile badge explorer',
                'description_key' => 'Profile badge explorer desc',
                'icon' => 'map-pin',
                'earned' => $metrics['unique_rooms_played_count'] >= 5,
                'current' => min($metrics['unique_rooms_played_count'], 5),
                'target' => 5,
                'progress_url' => route('home'),
            ],
            [
                'id' => 'regular',
                'label_key' => 'Profile badge regular',
                'description_key' => 'Profile badge regular desc',
                'icon' => 'flame',
                'earned' => $metrics['consecutive_days_streak'] >= 7,
                'current' => min($metrics['consecutive_days_streak'], 7),
                'target' => 7,
                'progress_url' => route('home'),
            ],
            [
                'id' => 'minigamer',
                'label_key' => 'Profile badge minigamer',
                'description_key' => 'Profile badge minigamer desc',
                'icon' => 'gamepad',
                'earned' => $metrics['minigame_scores_total'] > 0,
                'current' => min($metrics['minigame_scores_total'], 1),
                'target' => 1,
                'progress_url' => route('minigames.index'),
            ],
            [
                'id' => 'veteran',
                'label_key' => 'Profile badge veteran',
                'description_key' => 'Profile badge veteran desc',
                'icon' => 'badge-check',
                'earned' => $metrics['months_seniority'] >= 12 || $metrics['rounds_played_count'] >= 100,
                'current' => max(min($metrics['months_seniority'], 12), min($metrics['rounds_played_count'], 100)),
                'target' => 100,
                'progress_url' => route('home'),
            ],
            [
                'id' => 'top_elo',
                'label_key' => 'Profile badge top elo',
                'description_key' => 'Profile badge top elo desc',
                'icon' => 'trophy',
                'earned' => $eloRank !== null && $eloRank <= 100,
                'current' => $eloRank !== null && $eloRank <= 100
                    ? 100
                    : ($eloRank !== null ? max(0, 100 - ($eloRank - 100)) : 0),
                'target' => 100,
                'progress_url' => route('rankings.index', ['sort' => 'elo']),
            ],
            [
                'id' => 'collector',
                'label_key' => 'Profile badge collector',
                'description_key' => 'Profile badge collector desc',
                'icon' => 'heart',
                'earned' => $metrics['tracks_liked_count'] >= 50,
                'current' => min($metrics['tracks_liked_count'], 50),
                'target' => 50,
                'progress_url' => route('user.profile', ['user' => $user, 'tab' => 'likes']),
            ],
        ];

        return collect($definitions)
            ->sortByDesc(fn (array $badge) => $badge['earned'])
            ->values()
            ->all();
    }
}
