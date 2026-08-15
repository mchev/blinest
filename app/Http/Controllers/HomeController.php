<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Minigames\MinigameController;
use App\Models\Category;
use App\Models\MinigameScore;
use App\Models\Room;
use App\Models\User;
use App\Services\Minigames\MinigameScoreService;
use App\Services\RoomPresenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class HomeController extends Controller
{
    private const ROOMS_PER_CATEGORY = 12;

    private const PRIVATE_ROOMS_LIMIT = 18;

    private const HOMEPAGE_CACHE_SECONDS = 60;

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

        return $collection
            ->map(function ($room) use ($counts) {
                $id = is_array($room) ? $room['id'] : $room->id;
                $payload = is_array($room) ? $room : $room->toHomepageArray();

                return array_merge($payload, [
                    'subscriptions' => $counts[$id] ?? 0,
                ]);
            })
            ->values()
            ->all();
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
     * @return list<array{id: int, name: string, rooms: list<array<string, mixed>>}>
     */
    private function cachedCategoryRooms(): array
    {
        return Cache::remember('homepage-categories-v2', now()->addSeconds(self::HOMEPAGE_CACHE_SECONDS), function () {
            return Category::query()
                ->with(['rooms' => function ($query) {
                    $query->isPublic()
                        ->whereNull('password')
                        ->with('owner')
                        ->orderByDesc('is_playing')
                        ->orderByDesc('is_public')
                        ->limit(self::ROOMS_PER_CATEGORY);
                }])
                ->get()
                ->map(fn (Category $category) => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'rooms' => $category->rooms
                        ->map(fn (Room $room) => array_merge(
                            $room->toHomepageArray(),
                            ['owner' => $room->owner?->toArray()],
                        ))
                        ->values()
                        ->all(),
                ])
                ->values()
                ->all();
        });
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
            'categories' => collect($this->cachedCategoryRooms())
                ->map(fn (array $category) => [
                    'id' => $category['id'],
                    'name' => $category['name'],
                    'rooms' => $this->withPresenceCount($category['rooms']),
                ])
                ->all(),
            'private_rooms' => $this->withPresenceCount($this->cachedPrivateRooms()),
            'user_rooms' => $user
                ? $this->withPresenceCount($this->cachedModeratedRoomsForUser($user))
                : null,
        ]);
    }
}
