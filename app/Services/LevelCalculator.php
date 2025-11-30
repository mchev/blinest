<?php

namespace App\Services;

use App\Events\UserLevelUpdated;
use App\Models\User;
use App\Models\UserLevel;
use Illuminate\Support\Facades\DB;

class LevelCalculator
{
    /**
     * Calculate the total XP for a user based on various factors.
     * Uses cached values from user_levels when available to avoid expensive queries.
     */
    public function calculateTotalXp(User $user, ?UserLevel $userLevel = null): int
    {
        $userLevel = $userLevel ?? $user->userLevel;

        // 1. Score from public rooms (main factor) - 1 point = 1 XP
        // Use TotalScore table (already aggregated) instead of scores table
        $scorePublicRooms = $this->getScoreFromPublicRooms($user);

        // 2. Seniority bonus - 50 XP per month (max 12 months = 600 XP)
        // Recalculate as it changes over time
        $monthsSeniority = $user->created_at ? $user->created_at->diffInMonths(now()) : 0;
        $seniorityBonus = min($monthsSeniority * 50, 600);

        // 3. Rooms created bonus - 100 XP per room created (max 10 rooms = 1000 XP)
        // Use cached value if available, otherwise calculate (rarely changes)
        $roomsCreatedCount = $userLevel?->rooms_created_count ?? $user->rooms()->count();
        $roomsCreatedBonus = min($roomsCreatedCount * 100, 1000);

        // 4. Team membership bonus - 200 XP if in a team
        // Recalculate as it can change
        $teamBonus = $user->hasTeam() ? 200 : 0;

        // 5. Playlists created bonus - 50 XP per playlist (max 20 playlists = 1000 XP)
        // Use cached value if available, otherwise calculate (rarely changes)
        $playlistsCreatedCount = $userLevel?->playlists_created_count ?? $user->playlists()->count();
        $playlistsCreatedBonus = min($playlistsCreatedCount * 50, 1000);

        // 6. Rounds played bonus - 2 XP per round played (max 500 rounds = 1000 XP)
        // Use cached value if available, otherwise calculate
        // $roundsPlayedCount = $userLevel?->rounds_played_count ?? (int) DB::table('scores')
        //     ->where('user_id', $user->id)
        //     ->distinct()
        //     ->count('round_id');
        // $roundsPlayedBonus = min($roundsPlayedCount * 2, 1000);

        // 7. Correct answers bonus - 1 XP per 10 correct answers (max 5000 answers = 500 XP)
        // Use cached value if available, otherwise calculate (use count('id') for better performance)
        // $correctAnswersCount = $userLevel?->correct_answers_count ?? (int) DB::table('scores')
        //     ->where('user_id', $user->id)
        //     ->count('id');
        // $correctAnswersBonus = min(intval($correctAnswersCount / 10), 500);

        // 8. Tracks liked bonus - 5 XP per track liked (max 200 tracks = 1000 XP)
        // Use cached value if available, otherwise calculate (rarely changes)
        $tracksLikedCount = $userLevel?->tracks_liked_count ?? $user->likes()->count();
        $tracksLikedBonus = min($tracksLikedCount * 5, 1000);

        // 9. Messages sent bonus - 1 XP per 5 messages (max 1000 messages = 200 XP)
        // Use cached value if available, otherwise calculate (rarely changes)
        // $messagesCount = $userLevel?->messages_count ?? $user->messages()->count();
        // $messagesBonus = min(intval($messagesCount / 5), 200);

        // 10. Unique rooms played bonus - 10 XP per unique room (max 50 rooms = 500 XP)
        // Use cached value if available, otherwise calculate (optimized with subquery)
        // $uniqueRoomsCount = $userLevel?->unique_rooms_played_count ?? (int) DB::table('rounds')
        //     ->whereIn('id', function ($query) use ($user) {
        //         $query->select('round_id')
        //             ->from('scores')
        //             ->where('user_id', $user->id)
        //             ->distinct();
        //     })
        //     ->distinct()
        //     ->count('room_id');
        // $uniqueRoomsBonus = min($uniqueRoomsCount * 10, 500);

        // 11. Consecutive days streak bonus - 10 XP per day (max 30 days = 300 XP)
        // Recalculate as it changes daily
        // $consecutiveDaysStreak = $this->calculateConsecutiveDaysStreak($user);
        // $streakBonus = min($consecutiveDaysStreak * 10, 300);

        // Total XP
        return (int) (
            $scorePublicRooms +
            $seniorityBonus +
            $roomsCreatedBonus +
            $teamBonus +
            $playlistsCreatedBonus +
            // $roundsPlayedBonus +
            // $correctAnswersBonus +
            $tracksLikedBonus
            // $messagesBonus +
            // $uniqueRoomsBonus +
            // $streakBonus
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
        // Optimize: Use whereIn instead of whereHas to avoid subquery
        $publicRoomIds = DB::table('rooms')
            ->where('is_public', true)
            ->whereNull('password')
            ->pluck('id');

        if ($publicRoomIds->isEmpty()) {
            return 0.0;
        }

        return (float) $user->totalScores()
            ->whereIn('room_id', $publicRoomIds)
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
     * Calculate and update user level.
     * Optimized to use cached values from user_levels and TotalScore table.
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

        // Use cached values when available to avoid expensive queries
        $scorePublicRooms = $this->getScoreFromPublicRooms($user);
        $monthsSeniority = $user->created_at ? $user->created_at->diffInMonths(now()) : 0;

        // Use cached values for metrics that rarely change
        $roomsCreatedCount = $userLevel?->rooms_created_count ?? $user->rooms()->count();
        $playlistsCreatedCount = $userLevel?->playlists_created_count ?? $user->playlists()->count();
        $tracksLikedCount = $userLevel?->tracks_liked_count ?? $user->likes()->count();
        $messagesCount = $userLevel?->messages_count ?? $user->messages()->count();

        // For metrics that change frequently, recalculate but use cached if recent
        // Only recalculate if last calculation was more than 1 hour ago
        $shouldRecalculateMetrics = ! $userLevel || ! $userLevel->last_calculated_at || $userLevel->last_calculated_at->lt(now()->subHour());

        if ($shouldRecalculateMetrics) {
            // Recalculate expensive metrics using optimized queries
            // Use subqueries to avoid loading all data into memory

            // Rounds played: count distinct round_ids
            $roundsPlayedCount = (int) DB::table('scores')
                ->where('user_id', $user->id)
                ->distinct()
                ->count('round_id');

            // Correct answers: simple count (already indexed on user_id)
            $correctAnswersCount = (int) DB::table('scores')
                ->where('user_id', $user->id)
                ->count('id');

            // Unique rooms: optimized with exists subquery
            $uniqueRoomsCount = (int) DB::table('rounds')
                ->whereIn('id', function ($query) use ($user) {
                    $query->select('round_id')
                        ->from('scores')
                        ->where('user_id', $user->id)
                        ->distinct();
                })
                ->distinct()
                ->count('room_id');
        } else {
            // Use cached values
            $roundsPlayedCount = $userLevel->rounds_played_count ?? 0;
            $correctAnswersCount = $userLevel->correct_answers_count ?? 0;
            $uniqueRoomsCount = $userLevel->unique_rooms_played_count ?? 0;
        }

        // Best round score - use cached if recent, otherwise recalculate
        $bestRoundScore = 0;
        $consecutiveDaysStreak = $this->calculateConsecutiveDaysStreak($user);

        // Calculate total XP using cached values
        $totalXp = $this->calculateTotalXp($user, $userLevel);
        $levelData = $this->calculateLevel($totalXp);

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
