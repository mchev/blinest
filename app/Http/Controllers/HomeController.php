<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Minigames\MinigameController;
use App\Models\Category;
use App\Models\MinigameScore;
use App\Models\Room;
use App\Models\Round;
use App\Models\User;
use App\Services\Minigames\MinigameScoreService;
use App\Services\RoomPresenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class HomeController extends Controller
{
    private const PRIVATE_ROOMS_LIMIT = 18;

    private const HOMEPAGE_CACHE_SECONDS = 60;

    /**
     * @param  list<array<string, mixed>>  $rooms
     * @return list<array<string, mixed>>
     */
    private function overlayLiveRoomState(array $rooms): array
    {
        if ($rooms === []) {
            return [];
        }

        $ids = array_column($rooms, 'id');

        $liveRounds = Round::query()
            ->select(['rounds.id', 'rounds.room_id', 'rounds.current'])
            ->join('rooms', 'rooms.id', '=', 'rounds.room_id')
            ->whereIn('rounds.room_id', $ids)
            ->where('rooms.is_playing', true)
            ->where('rounds.is_playing', true)
            ->whereNull('rounds.finished_at')
            ->orderByDesc('rounds.id')
            ->get()
            ->unique('room_id')
            ->keyBy('room_id');

        return array_map(function (array $room) use ($liveRounds): array {
            $round = $liveRounds->get($room['id']);

            if ($round) {
                $room['is_playing'] = true;
                $room['current_track_index'] = (int) ($round->current ?? 0);
            } else {
                $room['is_playing'] = false;
                $room['current_track_index'] = 0;
            }

            return $room;
        }, $rooms);
    }

    /**
     * @param  Collection<int, Room>|list<array<string, mixed>>  $rooms
     * @return list<array<string, mixed>>
     */
    private function withPresenceCount(Collection|array $rooms): array
    {
        $collection = $rooms instanceof Collection ? $rooms : collect($rooms);

        if ($collection->isEmpty()) {
            return [];
        }

        $roomPresence = app(RoomPresenceService::class);
        $counts = $roomPresence->getMemberCountsForRooms($collection);

        $payload = $collection
            ->map(function ($room) use ($counts) {
                $id = is_array($room) ? $room['id'] : $room->id;
                $payload = is_array($room) ? $room : $room->toHomepageArray();

                return array_merge($payload, [
                    'subscriptions' => $counts[$id] ?? 0,
                ]);
            })
            ->values()
            ->all();

        return $this->overlayLiveRoomState($payload);
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function cachedModeratedRoomsForUser(User $user): array
    {
        return Cache::remember('homepage-moderatedrooms-'.$user->id, now()->addDay(), function () use ($user) {
            return $user->moderatedRooms()
                ->isPrivate()
                ->with('owner')
                ->get()
                ->map(fn (Room $room) => array_merge(
                    $room->toHomepageArray(),
                    ['owner' => $room->owner?->toArray()],
                ))
                ->values()
                ->all();
        });
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function cachedFeaturedRooms(): array
    {
        return Cache::remember('homepage-featured-rooms-v2', now()->addSeconds(self::HOMEPAGE_CACHE_SECONDS), function () {
            return Room::query()
                ->where('is_featured', true)
                ->whereNull('password')
                ->with('owner')
                ->get()
                ->map(fn (Room $room) => array_merge(
                    $room->toHomepageArray(),
                    ['owner' => $room->owner?->toArray()],
                ))
                ->values()
                ->all();
        });
    }

    /**
     * @return list<array{id: int, name: string, rooms_count: int}>
     */
    private function cachedPublicCategories(): array
    {
        return Cache::remember('homepage-public-categories-v3', now()->addSeconds(self::HOMEPAGE_CACHE_SECONDS), function () {
            return Category::query()
                ->whereHas('rooms', function ($query) {
                    $query->isPublic()->whereNull('password');
                })
                ->withCount(['rooms' => function ($query) {
                    $query->isPublic()->whereNull('password');
                }])
                ->orderBy('name')
                ->get(['id', 'name'])
                ->map(fn (Category $category) => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'rooms_count' => $category->rooms_count,
                ])
                ->values()
                ->all();
        });
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function cachedPublicRooms(): array
    {
        return Cache::remember('homepage-public-rooms-v3', now()->addSeconds(self::HOMEPAGE_CACHE_SECONDS), function () {
            return Room::query()
                ->isPublic()
                ->whereNull('password')
                ->with(['owner', 'category:id,name'])
                ->orderByDesc('is_playing')
                ->get()
                ->map(fn (Room $room) => array_merge(
                    $room->toHomepageArray(),
                    [
                        'owner' => $room->owner?->toArray(),
                        'category' => $room->category ? [
                            'id' => $room->category->id,
                            'name' => $room->category->name,
                        ] : null,
                    ],
                ))
                ->values()
                ->all();
        });
    }

    /**
     * @param  list<array<string, mixed>>  $rooms
     * @return list<array<string, mixed>>
     */
    private function sortRoomsByPresence(array $rooms): array
    {
        return collect($rooms)
            ->sortByDesc(fn (array $room) => [
                (int) ($room['subscriptions'] ?? 0),
                (int) ($room['is_playing'] ?? false),
            ])
            ->values()
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function cachedPrivateRooms(): array
    {
        return Cache::remember('homepage-private-rooms-v2', now()->addSeconds(self::HOMEPAGE_CACHE_SECONDS), function () {
            return Room::query()
                ->isPrivate()
                ->whereNull('password')
                ->with('owner')
                ->orderByDesc('is_playing')
                ->limit(self::PRIVATE_ROOMS_LIMIT)
                ->get()
                ->map(fn (Room $room) => array_merge(
                    $room->toHomepageArray(),
                    ['owner' => $room->owner?->toArray()],
                ))
                ->values()
                ->all();
        });
    }

    public function index(Request $request)
    {
        if ($request->only('search')) {
            $searchResult = Room::query()
                ->whereHas('playlists')
                ->whereNull('password')
                ->filter($request->only('search'))
                ->with('owner')
                ->withCount('rounds')
                ->orderByDesc('is_playing')
                ->orderByDesc('is_public')
                ->orderByDesc('rounds_count')
                ->limit(20)
                ->get();

            return Inertia::render('Home/Index', [
                'filters' => $request->all('search'),
                'search_result' => $this->withPresenceCount($searchResult),
            ]);
        }

        $user = $request->user();

        $minigameScores = $user
            ? app(MinigameScoreService::class)->getTotalsByTypeForUser($user)
            : array_combine(array_keys(MinigameScore::typeLabels()), array_fill(0, count(MinigameScore::typeLabels()), 0));

        return Inertia::render('Home/Index', [
            'filters' => $request->all('search'),
            'minigames' => MinigameController::buildGamesList($minigameScores),
            'weekly_top_users' => Cache::get('weekly-top-10-users'),
            'featured_rooms' => $this->withPresenceCount($this->cachedFeaturedRooms()),
            'public_categories' => $this->cachedPublicCategories(),
            'public_rooms' => $this->sortRoomsByPresence(
                $this->withPresenceCount($this->cachedPublicRooms()),
            ),
            'private_rooms' => $this->withPresenceCount($this->cachedPrivateRooms()),
            'user_rooms' => $user
                ? $this->withPresenceCount($this->cachedModeratedRoomsForUser($user))
                : null,
        ]);
    }
}
