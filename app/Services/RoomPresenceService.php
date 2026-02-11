<?php

namespace App\Services;

use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redis;

class RoomPresenceService
{
    /** Key expiry so empty rooms don't linger. */
    private const KEY_TTL = 7200;

    private const KEY_MEMBERS = 'room:%d:members';

    /**
     * Add member to room presence (called when user joins via HTTP presence-joined).
     * The authoritative list for the in-room UI is Laravel Echo's presence channel (.here, .joining, .leaving).
     * This Redis set is used for: GET /joined initial state, room count on home page.
     */
    public function addMember(Room $room, User $user): void
    {
        $key = sprintf(self::KEY_MEMBERS, $room->id);
        Redis::sadd($key, $user->id);
        Redis::expire($key, self::KEY_TTL);
    }

    public function removeMember(Room $room, User $user): void
    {
        $key = sprintf(self::KEY_MEMBERS, $room->id);
        Redis::srem($key, $user->id);
    }

    /**
     * @return array<int>
     */
    public function getMemberIds(Room $room): array
    {
        $key = sprintf(self::KEY_MEMBERS, $room->id);
        $ids = Redis::smembers($key);

        return array_map('intval', $ids);
    }

    public function getMemberCount(Room $room): int
    {
        $key = sprintf(self::KEY_MEMBERS, $room->id);

        return (int) Redis::scard($key);
    }

    /**
     * Get member counts for multiple rooms in one Redis round-trip (pipeline).
     *
     * @return array<int, int> room_id => count
     */
    public function getMemberCountsForRooms(Collection $rooms): array
    {
        if ($rooms->isEmpty()) {
            return [];
        }

        $results = Redis::pipeline(function ($pipe) use ($rooms) {
            foreach ($rooms as $room) {
                $key = sprintf(self::KEY_MEMBERS, $room->id);
                $pipe->scard($key);
            }
        });

        $counts = [];
        foreach ($rooms->values() as $index => $room) {
            $counts[$room->id] = (int) ($results[$index] ?? 0);
        }

        return $counts;
    }

    /**
     * Full room state for broadcasting and API: users (same shape as presence channel), scores, roundId.
     *
     * @return array{users: array<int, array>, scores: array<int, float>, roundId: int|null}
     */
    public function getRoomState(Room $room): array
    {
        $memberIds = $this->getMemberIds($room);
        $users = [];

        if (! empty($memberIds)) {
            $members = User::query()
                ->whereIn('id', $memberIds)
                ->with(['userLevel', 'team'])
                ->get();

            foreach ($members as $user) {
                $users[] = $this->formatUserForRoom($user, $room);
            }
        }

        $roundId = null;
        $scores = [];

        $currentRound = $room->currentRound()->first();
        if ($currentRound && ! $currentRound->finished_at) {
            $roundId = $currentRound->id;
            $scores = app(RoundScoreService::class)->getAllScores($currentRound->id);
        }

        return [
            'users' => $users,
            'scores' => $scores,
            'roundId' => $roundId,
        ];
    }

    /**
     * Same shape as Broadcast::channel('rooms.{room}') for consistency.
     */
    private function formatUserForRoom(User $user, Room $room): array
    {
        $user->loadMissing('userLevel');

        $publicRoundsPlayed = \App\Models\RoundStanding::where('user_id', $user->id)
            ->where('is_elo_counted', true)
            ->count();

        return [
            'id' => $user->id,
            'name' => $user->name,
            'team' => $user->team,
            'photo' => $user->photo,
            'elo' => $user->elo ?? 1500,
            'level' => $user->userLevel?->level ?? 1,
            'current_xp' => $user->userLevel?->current_xp ?? 0,
            'xp_for_next_level' => $user->userLevel?->xp_for_next_level ?? 100,
            'total_xp' => $user->userLevel?->total_xp ?? 0,
            'userLevel' => $user->userLevel ? [
                'level' => $user->userLevel->level,
                'rounds_played_count' => $user->userLevel->rounds_played_count ?? 0,
            ] : null,
            'public_rounds_played_count' => $publicRoundsPlayed,
            'level_metrics' => $user->userLevel ? [
                'score_public_rooms' => $user->userLevel->score_public_rooms ?? 0,
                'seniority_months' => $user->userLevel->months_seniority ?? 0,
                'consecutive_days_streak' => $user->userLevel->consecutive_days_streak ?? 0,
                'rooms_created_count' => $user->userLevel->rooms_created_count ?? 0,
                'playlists_created_count' => $user->userLevel->playlists_created_count ?? 0,
                'tracks_liked_count' => $user->userLevel->tracks_liked_count ?? 0,
                'has_team' => $user->team !== null,
            ] : null,
        ];
    }
}
