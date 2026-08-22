<?php

namespace App\Services\Profiles;

use App\Models\User;

class ProfileBadgeService
{
    /**
     * @return list<array{id: string, label_key: string, icon: string, earned: bool}>
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
                'icon' => 'crown',
                'earned' => $isSupporter,
            ],
            [
                'id' => 'creator',
                'label_key' => 'Profile badge creator',
                'icon' => 'sparkles',
                'earned' => $metrics['rooms_created_count'] >= 1,
            ],
            [
                'id' => 'explorer',
                'label_key' => 'Profile badge explorer',
                'icon' => 'map-pin',
                'earned' => $metrics['unique_rooms_played_count'] >= 5,
            ],
            [
                'id' => 'regular',
                'label_key' => 'Profile badge regular',
                'icon' => 'flame',
                'earned' => $metrics['consecutive_days_streak'] >= 7,
            ],
            [
                'id' => 'minigamer',
                'label_key' => 'Profile badge minigamer',
                'icon' => 'gamepad',
                'earned' => $metrics['minigame_scores_total'] > 0,
            ],
            [
                'id' => 'veteran',
                'label_key' => 'Profile badge veteran',
                'icon' => 'badge-check',
                'earned' => $metrics['months_seniority'] >= 12 || $metrics['rounds_played_count'] >= 100,
            ],
            [
                'id' => 'top_elo',
                'label_key' => 'Profile badge top elo',
                'icon' => 'trophy',
                'earned' => $eloRank !== null && $eloRank <= 100,
            ],
            [
                'id' => 'collector',
                'label_key' => 'Profile badge collector',
                'icon' => 'heart',
                'earned' => $metrics['tracks_liked_count'] >= 50,
            ],
        ];

        return collect($definitions)
            ->sortByDesc(fn (array $badge) => $badge['earned'])
            ->values()
            ->all();
    }
}
