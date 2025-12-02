<?php

namespace App\Services;

use App\Events\UserLevelUpdated;
use App\Models\Room;
use App\Models\User;
use App\Models\UserLevel;
use Illuminate\Support\Facades\Cache;

class LevelCalculator
{
    /** List of metrics
     *
     * - login 7 days in a row (10 XP per week streak)
     * - score (1 point = 1 XP)
     * - likes count (5 XP per like)
     * - playlists count (20 XP per playlist created)
     * - rooms count (50 XP per room created)
     * - has a team (200 XP if in a team)
     */
    public UserLevel $userLevel;

    public function __construct(
        public User $user,
        public string $type = 'score',
    ) {
        //
    }

    public function update()
    {

        $this->userLevel = $this->getUserLevel();

        switch ($this->type) {
            case 'login':
                $this->updateLogin();
                break;
            case 'score':
                $this->updateScore();
                break;
            case 'playlists_count':
                $this->updatePlaylist();
                break;
            case 'rooms_count':
                $this->updateRoom();
                break;
            case 'likes_count':
                $this->updateLikes();
                break;
        }

        $this->calculateLevelData();

        // Remove xp_needed if it exists (it's not a database column)
        // Use setRawAttributes to ensure it's not saved
        $attributes = $this->userLevel->getAttributes();
        unset($attributes['xp_needed']);
        $this->userLevel->setRawAttributes($attributes);

        $this->userLevel->save();

        // Refresh user to ensure we have the latest userLevel data
        $this->user->refresh();
        $this->user->load('userLevel');

        // Broadcast the level update event
        broadcast(new UserLevelUpdated($this->user, [
            'level' => $this->userLevel->level,
            'current_xp' => $this->userLevel->current_xp,
            'xp_for_next_level' => $this->userLevel->xp_for_next_level,
            'total_xp' => $this->userLevel->total_xp,
        ]));

        return $this->userLevel;
    }

    // Update login date and consecutive days streak
    public function updateLogin(): void
    {
        $lastLoginDate = $this->userLevel->last_login_date;
        $consecutiveDaysStreak = $this->userLevel->consecutive_days_streak ?? 0;

        if ($lastLoginDate) {
            $yesterday = now()->subDay()->toDateString();
            $today = now()->toDateString();
            $lastLoginDateString = $lastLoginDate->toDateString();

            // If already logged in today, don't change the streak
            if ($lastLoginDateString === $today) {
                // Keep current streak, don't update
                return;
            }

            // If last login was yesterday, increment streak
            if ($lastLoginDateString === $yesterday) {
                $consecutiveDaysStreak++;
            } else {
                // Last login was before yesterday, reset streak to 1
                $consecutiveDaysStreak = 1;
            }
        } else {
            // First login ever, start streak at 1
            $consecutiveDaysStreak = 1;
        }

        $this->userLevel->last_login_date = now()->toDateString();
        $this->userLevel->consecutive_days_streak = $consecutiveDaysStreak;
    }

    public function updateScore(): void
    {
        $publicRoomIds = Cache::flexible('public_room_ids', [518400, 604800], function () {
            return Room::isPublic()->pluck('id');
        });

        $totalScore = $this->user->totalScores()
            ->whereIn('room_id', $publicRoomIds)
            ->sum('score');

        $this->userLevel->score_public_rooms = $totalScore;
    }

    public function updatePlaylist(): void
    {
        $playlistsCount = $this->user->playlists()->count();

        $this->userLevel->playlists_created_count = $playlistsCount;
    }

    public function updateRoom(): void
    {
        $roomsCount = $this->user->rooms()->count();

        $this->userLevel->rooms_created_count = $roomsCount;
    }

    public function updateLikes(): void
    {
        // Use the likes() method which is more reliable
        $likesCount = $this->user->likes()->count();

        $this->userLevel->tracks_liked_count = $likesCount;
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
    public function calculateLevelData(): void
    {
        $userSeniorityInMonths = Cache::remember('user_seniority_in_months_v2_'.$this->user->id, 604800, function () {
            return (int) round($this->user->created_at->diffInMonths(now()));
        });

        $totalXp = $this->userLevel->score_public_rooms
            + min($userSeniorityInMonths * 50, 600)
            + min($this->userLevel->rooms_created_count * 100, 1000)
            + ($this->user->hasTeam() ? 200 : 0)
            + min($this->userLevel->playlists_created_count * 20, 2000)
            + min($this->userLevel->tracks_liked_count * 5, 1000)
            + min($this->userLevel->consecutive_days_streak * 10, 300);

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

        $this->userLevel->level = $level;
        $this->userLevel->total_xp = $totalXp;
        $this->userLevel->current_xp = max(0, $currentXp);
        $this->userLevel->xp_for_next_level = $xpForNextLevel;
        $this->userLevel->last_calculated_at = now();
        $this->userLevel->months_seniority = $userSeniorityInMonths;

    }

    // Check if UserLevel exists for the user, if not, initialize it
    public function getUserLevel(): UserLevel
    {
        $userLevel = $this->user->userLevel;

        if (! $userLevel) {
            $userLevel = UserLevel::create([
                'user_id' => $this->user->id,
                'level' => 1,
                'total_xp' => 0,
                'current_xp' => 0,
                'score_public_rooms' => 0,
                'rooms_created_count' => 0,
                'months_seniority' => 0,
                'rounds_played_count' => 0,
                'correct_answers_count' => 0,
                'tracks_liked_count' => 0,
                'messages_count' => 0,
                'playlists_created_count' => 0,
                'unique_rooms_played_count' => 0,
                'best_round_score' => 0,
                'consecutive_days_streak' => 0,
                'last_login_date' => null,
                'last_calculated_at' => null,
            ]);
        }

        return $userLevel;
    }
}
