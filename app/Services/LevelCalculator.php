<?php

namespace App\Services;

use App\Events\UserLevelUpdated;
use App\Models\User;
use App\Models\UserLevel;

class LevelCalculator
{
    /**
     * Calculate the total XP for a user based on various factors.
     */
    public function calculateTotalXp(User $user): int
    {
        // 1. Score from public rooms (main factor) - 1 point = 1 XP
        $scorePublicRooms = $this->getScoreFromPublicRooms($user);

        // 2. Seniority bonus - 50 XP per month (max 12 months = 600 XP)
        $monthsSeniority = $user->created_at->diffInMonths(now());
        $seniorityBonus = min($monthsSeniority * 50, 600);

        // 3. Rooms created bonus - 100 XP per room created (max 10 rooms = 1000 XP)
        $roomsCreatedCount = $user->rooms()->count();
        $roomsCreatedBonus = min($roomsCreatedCount * 100, 1000);

        // 4. Team membership bonus - 200 XP if in a team
        $teamBonus = $user->hasTeam() ? 200 : 0;

        // 5. Playlists created bonus - 50 XP per playlist (max 20 playlists = 1000 XP)
        $playlistsCreatedCount = $user->playlists()->count();
        $playlistsCreatedBonus = min($playlistsCreatedCount * 50, 1000);

        // 6. Rounds played bonus - 2 XP per round played (max 500 rounds = 1000 XP)
        $roundsPlayedCount = $user->scores()->distinct()->count('round_id');
        $roundsPlayedBonus = min($roundsPlayedCount * 2, 1000);

        // 7. Correct answers bonus - 1 XP per 10 correct answers (max 5000 answers = 500 XP)
        $correctAnswersCount = $user->scores()->count();
        $correctAnswersBonus = min(intval($correctAnswersCount / 10), 500);

        // 8. Tracks liked bonus - 5 XP per track liked (max 200 tracks = 1000 XP)
        $tracksLikedCount = $user->likes()->count();
        $tracksLikedBonus = min($tracksLikedCount * 5, 1000);

        // 9. Messages sent bonus - 1 XP per 5 messages (max 1000 messages = 200 XP)
        $messagesCount = $user->messages()->count();
        $messagesBonus = min(intval($messagesCount / 5), 200);

        // 10. Unique rooms played bonus - 10 XP per unique room (max 50 rooms = 500 XP)
        $uniqueRoomsCount = $user->scores()
            ->join('rounds', 'scores.round_id', '=', 'rounds.id')
            ->distinct()
            ->count('rounds.room_id');
        $uniqueRoomsBonus = min($uniqueRoomsCount * 10, 500);

        // 11. Consecutive days streak bonus - 10 XP per day (max 30 days = 300 XP)
        $consecutiveDaysStreak = $this->calculateConsecutiveDaysStreak($user);
        $streakBonus = min($consecutiveDaysStreak * 10, 300);

        // Total XP
        return (int) (
            $scorePublicRooms +
            $seniorityBonus +
            $roomsCreatedBonus +
            $teamBonus +
            $playlistsCreatedBonus +
            $roundsPlayedBonus +
            $correctAnswersBonus +
            $tracksLikedBonus +
            $messagesBonus +
            $uniqueRoomsBonus +
            $streakBonus
        );
    }

    /**
     * Calculate level from total XP.
     * Formula: Progressive XP requirements that increase smoothly
     * Level 1: 0-99 XP (100 XP needed for level 2)
     * Level 2: 100-249 XP (150 XP needed for level 3)
     * Level 3: 250-449 XP (200 XP needed for level 4)
     * Level 4: 450-699 XP (250 XP needed for level 5)
     * Level 5: 700-999 XP (300 XP needed for level 6)
     * etc. (XP requirement increases by 50 each level, no maximum level)
     */
    public function calculateLevel(int $totalXp): array
    {
        $level = 1;
        $xpForCurrentLevel = 0;
        $baseXpForNextLevel = 100; // XP needed for level 2
        $xpIncrement = 50; // XP requirement increases by 50 each level

        // Calculate the XP threshold for the next level
        $xpForNextLevel = $baseXpForNextLevel;

        // Progress through levels until we find the correct one
        while ($totalXp >= $xpForNextLevel) {
            $xpForCurrentLevel = $xpForNextLevel;
            $level++;
            // XP needed for next level = previous threshold + (100 + (level-2) * 50)
            // This gives: 100, 150, 200, 250, 300, 350, etc.
            $xpForNextLevel = $xpForCurrentLevel + (100 + ($level - 2) * $xpIncrement);
        }

        $currentXp = $totalXp - $xpForCurrentLevel;
        $xpNeededForNext = $xpForNextLevel - $totalXp;

        return [
            'level' => $level,
            'total_xp' => $totalXp,
            'current_xp' => max(0, $currentXp),
            'xp_for_next_level' => $xpForNextLevel,
            'xp_needed' => max(0, $xpNeededForNext),
        ];
    }

    /**
     * Get total score from public rooms only.
     */
    public function getScoreFromPublicRooms(User $user): float
    {
        return (float) $user->totalScores()
            ->whereHas('room', function ($query) {
                $query->where('is_public', true)
                    ->whereNull('password');
            })
            ->sum('score');
    }

    /**
     * Calculate consecutive days streak based on login history.
     */
    public function calculateConsecutiveDaysStreak(User $user): int
    {
        $userLevel = $user->userLevel;
        $lastLoginDate = $userLevel?->last_login_date;
        $currentStreak = $userLevel?->consecutive_days_streak ?? 0;

        // If no last login date, start streak at 1 (first login today)
        if (! $lastLoginDate) {
            return 1;
        }

        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();
        $lastLoginDateString = $lastLoginDate->toDateString();

        // If last login was today, keep the current streak (don't increment)
        if ($lastLoginDateString === $today) {
            return $currentStreak;
        }

        // If last login was yesterday, increment streak
        if ($lastLoginDateString === $yesterday) {
            return $currentStreak + 1;
        }

        // If last login was more than 1 day ago, reset streak to 1 (today's login)
        return 1;
    }

    /**
     * Get best round score for a user.
     */
    public function getBestRoundScore(User $user): float
    {
        $bestScore = $user->scores()
            ->selectRaw('SUM(score) as round_total')
            ->groupBy('round_id')
            ->orderByDesc('round_total')
            ->limit(1)
            ->value('round_total');

        return $bestScore ? (float) $bestScore : 0.0;
    }

    /**
     * Calculate and update user level.
     */
    public function updateUserLevel(User $user, ?\DateTimeInterface $loginDate = null): UserLevel
    {
        // Update last_login_date if provided (for streak calculation)
        $today = ($loginDate ?? now())->toDateString();
        $userLevel = $user->userLevel;
        if ($loginDate && $userLevel && (! $userLevel->last_login_date || $userLevel->last_login_date->toDateString() !== $today)) {
            $userLevel->update(['last_login_date' => $today]);
            $userLevel->refresh();
        }

        $totalXp = $this->calculateTotalXp($user);
        $levelData = $this->calculateLevel($totalXp);

        $scorePublicRooms = $this->getScoreFromPublicRooms($user);
        $monthsSeniority = $user->created_at->diffInMonths(now());
        $roomsCreatedCount = $user->rooms()->count();
        $roundsPlayedCount = $user->scores()->distinct()->count('round_id');
        $correctAnswersCount = $user->scores()->count();
        $tracksLikedCount = $user->likes()->count();
        $messagesCount = $user->messages()->count();
        $playlistsCreatedCount = $user->playlists()->count();
        $uniqueRoomsCount = $user->scores()
            ->join('rounds', 'scores.round_id', '=', 'rounds.id')
            ->distinct()
            ->count('rounds.room_id');
        $bestRoundScore = $this->getBestRoundScore($user);
        $consecutiveDaysStreak = $this->calculateConsecutiveDaysStreak($user);

        $oldLevel = $userLevel?->level ?? 1;
        $newLevel = $levelData['level'];

        $userLevel = UserLevel::updateOrCreate(
            ['user_id' => $user->id],
            [
                'level' => $levelData['level'],
                'total_xp' => $levelData['total_xp'],
                'current_xp' => $levelData['current_xp'],
                'xp_for_next_level' => $levelData['xp_for_next_level'],
                'score_public_rooms' => (int) $scorePublicRooms,
                'rooms_created_count' => $roomsCreatedCount,
                'months_seniority' => $monthsSeniority,
                'rounds_played_count' => $roundsPlayedCount,
                'correct_answers_count' => $correctAnswersCount,
                'tracks_liked_count' => $tracksLikedCount,
                'messages_count' => $messagesCount,
                'playlists_created_count' => $playlistsCreatedCount,
                'unique_rooms_played_count' => $uniqueRoomsCount,
                'best_round_score' => $bestRoundScore,
                'consecutive_days_streak' => $consecutiveDaysStreak,
                'last_login_date' => $loginDate ? $loginDate->toDateString() : ($userLevel?->last_login_date ?? null),
                'last_calculated_at' => now(),
            ]
        );

        // Broadcast level update in real-time if level changed or XP changed
        if ($oldLevel !== $newLevel || $userLevel->current_xp !== $levelData['current_xp']) {
            try {
                broadcast(new UserLevelUpdated($user, $levelData));
            } catch (\Exception $e) {
                // Log error but don't fail the level update
                \Log::error('Failed to broadcast UserLevelUpdated event', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $userLevel;
    }
}
