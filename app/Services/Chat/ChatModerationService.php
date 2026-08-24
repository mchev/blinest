<?php

namespace App\Services\Chat;

use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ChatModerationService
{
    public function filterBody(string $body): string
    {
        $badwords = trans('bad-words');

        $badwordsregex = array_map(function ($word) {
            return '/\b'.preg_quote(trim($word), '/').'\b/iu';
        }, $badwords);

        return preg_replace($badwordsregex, '*****', $body) ?? $body;
    }

    public function assertCanSend(User $user, Room $room, string $body): void
    {
        if ($this->isStaff($user)) {
            return;
        }

        $normalizedBody = $this->normalizeBody($body);

        if ($normalizedBody === '') {
            throw ValidationException::withMessages([
                'body' => __('Chat message empty'),
            ]);
        }

        $this->assertNotFloodingRoom($user, $room);
        $this->assertNotDuplicateInRoom($user, $room, $normalizedBody);
        $this->assertNoSuspiciousLinks($body);
        $this->assertNotCrossRoomSpam($user, $room, $normalizedBody, $body);
    }

    public function recordSentMessage(User $user, Room $room, string $body): void
    {
        if ($this->isStaff($user)) {
            return;
        }

        $normalizedBody = $this->normalizeBody($body);

        if ($normalizedBody === '') {
            return;
        }

        $this->incrementRoomFloodCounter($user, $room);
        $this->rememberRoomDuplicate($user, $room, $normalizedBody);
        $this->rememberCrossRoomActivity($user, $room, $normalizedBody);
    }

    private function isStaff(User $user): bool
    {
        return $user->isAdministrator() || $user->isPublicModerator();
    }

    private function normalizeBody(string $body): string
    {
        $normalized = Str::lower(trim($body));
        $normalized = Str::transliterate($normalized);
        $normalized = preg_replace('/[\s\p{P}\p{S}]+/u', '', $normalized) ?? '';

        return $normalized;
    }

    private function assertNotFloodingRoom(User $user, Room $room): void
    {
        $count = (int) Cache::get($this->roomFloodCacheKey($user, $room), 0);

        if ($count >= (int) config('chat.moderation.room_flood_per_minute', 8)) {
            throw ValidationException::withMessages([
                'body' => __('Chat message flood'),
            ]);
        }
    }

    private function assertNotDuplicateInRoom(User $user, Room $room, string $normalizedBody): void
    {
        if (Cache::has($this->roomDuplicateCacheKey($user, $room, $normalizedBody))) {
            throw ValidationException::withMessages([
                'body' => __('Chat message duplicate'),
            ]);
        }
    }

    private function assertNoSuspiciousLinks(string $body): void
    {
        $pattern = (string) config('chat.moderation.suspicious_link_pattern');

        if ($pattern !== '' && preg_match($pattern, $body) === 1) {
            throw ValidationException::withMessages([
                'body' => __('Chat message links forbidden'),
            ]);
        }
    }

    private function assertNotCrossRoomSpam(User $user, Room $room, string $normalizedBody, string $originalBody): void
    {
        $minLength = (int) config('chat.moderation.min_cross_room_body_length', 8);

        if (mb_strlen($normalizedBody) < $minLength) {
            return;
        }

        $roomIds = $this->crossRoomRoomIds($user, $normalizedBody);
        $roomIds[] = $room->id;
        $distinctRoomCount = count(array_unique($roomIds));

        if ($distinctRoomCount < (int) config('chat.moderation.cross_room_min_rooms', 3)) {
            return;
        }

        if ($user->isBanned()) {
            throw ValidationException::withMessages([
                'body' => __('Chat message rejected spam'),
            ]);
        }

        $user->ban([
            'expired_at' => now()->addDays((int) config('chat.moderation.cross_room_ban_days', 7)),
            'comment' => __('Chat auto ban cross room spam', ['message' => Str::limit($originalBody, 120)]),
            'metas' => [
                'banned_by' => 'system',
                'reason' => 'cross_room_spam',
                'room_ids' => array_values(array_unique($roomIds)),
            ],
            'ip' => $user->ip,
        ]);

        throw ValidationException::withMessages([
            'body' => __('Chat message banned spam'),
        ]);
    }

    private function incrementRoomFloodCounter(User $user, Room $room): void
    {
        $key = $this->roomFloodCacheKey($user, $room);

        if (! Cache::has($key)) {
            Cache::put($key, 1, now()->addMinute());

            return;
        }

        Cache::increment($key);
    }

    private function rememberRoomDuplicate(User $user, Room $room, string $normalizedBody): void
    {
        Cache::put(
            $this->roomDuplicateCacheKey($user, $room, $normalizedBody),
            true,
            now()->addSeconds((int) config('chat.moderation.duplicate_window_seconds', 30)),
        );
    }

    /**
     * @return list<int>
     */
    private function crossRoomRoomIds(User $user, string $normalizedBody): array
    {
        $entries = Cache::get($this->crossRoomCacheKey($user, $normalizedBody), []);

        if (! is_array($entries)) {
            return [];
        }

        $windowStart = now()->subSeconds((int) config('chat.moderation.cross_room_window_seconds', 300))->timestamp;

        return collect($entries)
            ->filter(fn ($entry) => is_array($entry) && ($entry['at'] ?? 0) >= $windowStart)
            ->pluck('room_id')
            ->map(fn ($roomId) => (int) $roomId)
            ->all();
    }

    private function rememberCrossRoomActivity(User $user, Room $room, string $normalizedBody): void
    {
        $minLength = (int) config('chat.moderation.min_cross_room_body_length', 8);

        if (mb_strlen($normalizedBody) < $minLength) {
            return;
        }

        $key = $this->crossRoomCacheKey($user, $normalizedBody);
        $windowSeconds = (int) config('chat.moderation.cross_room_window_seconds', 300);
        $windowStart = now()->subSeconds($windowSeconds)->timestamp;
        $entries = Cache::get($key, []);

        if (! is_array($entries)) {
            $entries = [];
        }

        $entries = collect($entries)
            ->filter(fn ($entry) => is_array($entry) && ($entry['at'] ?? 0) >= $windowStart)
            ->values()
            ->all();

        $entries[] = [
            'room_id' => $room->id,
            'at' => now()->timestamp,
        ];

        Cache::put($key, $entries, now()->addSeconds($windowSeconds));
    }

    private function roomFloodCacheKey(User $user, Room $room): string
    {
        return "chat:flood:{$user->id}:room:{$room->id}";
    }

    private function roomDuplicateCacheKey(User $user, Room $room, string $normalizedBody): string
    {
        return 'chat:duplicate:'.$user->id.':'.$room->id.':'.sha1($normalizedBody);
    }

    private function crossRoomCacheKey(User $user, string $normalizedBody): string
    {
        return 'chat:cross-room:'.$user->id.':'.sha1($normalizedBody);
    }
}
