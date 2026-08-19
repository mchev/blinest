<?php

namespace App\Services;

use App\Http\Controllers\Minigames\MinigameController;
use App\Models\Category;
use App\Models\MinigameScore;
use App\Models\Room;
use App\Models\Round;
use App\Models\User;
use App\Services\Minigames\MinigameScoreService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class HomeCatalogService
{
    public const PER_PAGE = 16;

    private const HOMEPAGE_CACHE_SECONDS = 60;

    public function __construct(private RoomPresenceService $roomPresence) {}

    /** @var array<int, int>|null */
    private ?array $memoizedPublicPresenceCounts = null;

    /**
     * @return array{official: int, community: int}
     */
    public function catalogTabPlayerCounts(): array
    {
        return [
            'official' => array_sum($this->publicRoomPresenceCounts()),
            'community' => $this->communityTabPlayerCount(),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function featuredRooms(): array
    {
        return $this->overlayLiveRoomState(
            $this->applyPresenceCounts($this->cachedFeaturedRooms()),
        );
    }

    /**
     * @return list<array{id: int, name: string, rooms_count: int}>
     */
    public function publicCategories(): array
    {
        return Cache::remember('homepage-public-categories-v3', now()->addSeconds(self::HOMEPAGE_CACHE_SECONDS), function () {
            return $this->categoriesWithRoomCounts(isPublic: true);
        });
    }

    /**
     * @return list<array{id: int, name: string, rooms_count: int}>
     */
    public function communityCategories(): array
    {
        return Cache::remember('homepage-community-categories-v1', now()->addSeconds(self::HOMEPAGE_CACHE_SECONDS), function () {
            return $this->categoriesWithRoomCounts(isPublic: false);
        });
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function searchRooms(Request $request): array
    {
        $rooms = Room::query()
            ->whereHas('playlists')
            ->whereNull('password')
            ->filter($request->only('search'))
            ->with('owner')
            ->withCount('rounds')
            ->orderByDesc('is_playing')
            ->orderByDesc('is_public')
            ->orderByDesc('rounds_count')
            ->limit(20)
            ->get()
            ->map(fn (Room $room) => $this->mapRoomForHomepage($room))
            ->values()
            ->all();

        return $this->overlayLiveRoomState(
            $this->applyPresenceCounts($rooms),
        );
    }

    /**
     * @return list<string>
     */
    public function tabs(?User $user): array
    {
        $tabs = ['official', 'community'];

        if ($user !== null) {
            $tabs[] = 'mine';
        }

        $tabs[] = 'minigames';

        return $tabs;
    }

    public function resolveTab(Request $request): string
    {
        $tab = $request->string('tab', 'official')->toString();

        if (! in_array($tab, $this->tabs($request->user()), true)) {
            return 'official';
        }

        return $tab;
    }

    public function paginate(Request $request): LengthAwarePaginator
    {
        $tab = $this->resolveTab($request);

        if ($tab === 'community') {
            return $this->paginateCommunityCatalog($request);
        }

        $items = match ($tab) {
            'official' => $this->officialRooms($request),
            'mine' => $this->userRooms($request),
            'minigames' => $this->minigames($request),
            default => [],
        };

        return $this->paginateItems($items, $request);
    }

    private function paginateCommunityCatalog(Request $request): LengthAwarePaginator
    {
        $categoryId = $request->integer('category_id') ?: null;
        $pageName = 'catalog';
        $page = Paginator::resolveCurrentPage($pageName);

        $sortedIds = collect($this->communityRoomIndex())
            ->when(
                $categoryId !== null,
                fn (Collection $rows) => $rows->where('category_id', $categoryId),
            )
            ->pluck('id')
            ->values()
            ->all();

        $total = count($sortedIds);
        $pageIds = array_slice($sortedIds, ($page - 1) * self::PER_PAGE, self::PER_PAGE);

        if ($pageIds === []) {
            return new LengthAwarePaginator(
                [],
                $total,
                self::PER_PAGE,
                $page,
                [
                    'pageName' => $pageName,
                    'path' => Paginator::resolveCurrentPath(),
                    'query' => $request->query(),
                ],
            );
        }

        $roomsById = Room::query()
            ->whereIn('id', $pageIds)
            ->with(['owner', 'category:id,name'])
            ->withCount('rounds')
            ->get()
            ->keyBy('id');

        $rooms = collect($pageIds)
            ->map(fn (int $id) => $roomsById->get($id))
            ->filter()
            ->map(fn (Room $room) => $this->mapRoomForHomepage($room, includeTracksCount: true))
            ->values()
            ->all();

        $rooms = $this->sortRoomsByPopularity(
            $this->overlayLiveRoomState(
                $this->applyPresenceCounts($rooms),
            ),
        );

        return new LengthAwarePaginator(
            $rooms,
            $total,
            self::PER_PAGE,
            $page,
            [
                'pageName' => $pageName,
                'path' => Paginator::resolveCurrentPath(),
                'query' => $request->query(),
            ],
        );
    }

    /**
     * @param  list<array<string, mixed>>  $items
     */
    private function paginateItems(array $items, Request $request): LengthAwarePaginator
    {
        $pageName = 'catalog';
        $page = Paginator::resolveCurrentPage($pageName);
        $total = count($items);
        $results = array_slice($items, ($page - 1) * self::PER_PAGE, self::PER_PAGE);

        return new LengthAwarePaginator(
            $results,
            $total,
            self::PER_PAGE,
            $page,
            [
                'pageName' => $pageName,
                'path' => Paginator::resolveCurrentPath(),
                'query' => $request->query(),
            ],
        );
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function officialRooms(Request $request): array
    {
        $counts = $this->publicRoomPresenceCounts();

        $rooms = array_map(
            fn (array $room) => array_merge($room, [
                'subscriptions' => $counts[$room['id']] ?? 0,
            ]),
            $this->cachedPublicRooms(),
        );

        $rooms = $this->sortRoomsByPopularity(
            $this->overlayLiveRoomState($rooms),
        );

        $hiddenCategoryIds = config('blinest.homepage_hidden_category_ids', []);
        $categoryId = $request->integer('category_id') ?: null;

        return collect($rooms)
            ->when(
                $categoryId !== null,
                fn (Collection $collection) => $collection->filter(
                    fn (array $room) => ($room['category']['id'] ?? null) === $categoryId,
                ),
                fn (Collection $collection) => $collection->reject(
                    fn (array $room) => in_array($room['category']['id'] ?? null, $hiddenCategoryIds, true),
                ),
            )
            ->values()
            ->all();
    }

    /**
     * @return list<int>
     */
    private function cachedCommunityRoomIds(): array
    {
        return Cache::remember('homepage-community-room-ids-v1', now()->addSeconds(self::HOMEPAGE_CACHE_SECONDS), function () {
            return Room::query()
                ->isPrivate()
                ->whereNull('password')
                ->orderBy('id')
                ->pluck('id')
                ->all();
        });
    }

    /**
     * @return list<array{id: int, category_id: int|null, subscriptions: int, rounds_count: int}>
     */
    private function communityRoomIndex(): array
    {
        $roomIds = $this->cachedCommunityRoomIds();

        if ($roomIds === []) {
            return [];
        }

        $candidates = Room::query()
            ->whereIn('id', $roomIds)
            ->withCount('rounds')
            ->get(['id', 'category_id']);

        if ($candidates->isEmpty()) {
            return [];
        }

        $presenceCounts = $this->roomPresence->getMemberCountsForRooms($candidates);

        return $candidates
            ->map(fn (Room $room) => [
                'id' => $room->id,
                'category_id' => $room->category_id,
                'subscriptions' => $presenceCounts[$room->id] ?? 0,
                'rounds_count' => (int) ($room->rounds_count ?? 0),
            ])
            ->sortByDesc(fn (array $row) => [$row['subscriptions'], $row['rounds_count'], $row['id']])
            ->values()
            ->all();
    }

    private function communityTabPlayerCount(): int
    {
        $roomIds = $this->cachedCommunityRoomIds();

        if ($roomIds === []) {
            return 0;
        }

        return (int) array_sum(
            $this->roomPresence->getMemberCountsForRooms(
                collect($roomIds)->map(fn (int $id): array => ['id' => $id]),
            ),
        );
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function userRooms(Request $request): array
    {
        $user = $request->user();

        if ($user === null) {
            return [];
        }

        $rooms = Cache::remember('homepage-moderatedrooms-'.$user->id, now()->addDay(), function () use ($user) {
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

        return $this->sortRoomsByPopularity(
            $this->overlayLiveRoomState(
                $this->applyPresenceCounts(
                    $this->applyTracksCountsToPayload($rooms),
                ),
            ),
        );
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function minigames(Request $request): array
    {
        $user = $request->user();

        $scoresByType = $user
            ? app(MinigameScoreService::class)->getTotalsByTypeForUser($user)
            : array_combine(array_keys(MinigameScore::typeLabels()), array_fill(0, count(MinigameScore::typeLabels()), 0));

        return MinigameController::buildGamesList($scoresByType);
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function cachedPublicRooms(): array
    {
        return Cache::remember('homepage-public-rooms-v4', now()->addSeconds(self::HOMEPAGE_CACHE_SECONDS), function () {
            return Room::query()
                ->isPublic()
                ->whereNull('password')
                ->with(['owner', 'category:id,name'])
                ->withCount('rounds')
                ->orderByDesc('is_playing')
                ->get()
                ->map(fn (Room $room) => $this->mapRoomForHomepage($room))
                ->values()
                ->all();
        });
    }

    /**
     * @return array<string, mixed>
     */
    private function mapRoomForHomepage(Room $room, bool $includeTracksCount = false): array
    {
        $mapped = array_merge(
            $room->toHomepageArray(),
            [
                'owner' => $room->owner?->toArray(),
                'rounds_count' => (int) ($room->rounds_count ?? 0),
                'category' => $room->relationLoaded('category') && $room->category ? [
                    'id' => $room->category->id,
                    'name' => $room->category->name,
                ] : null,
            ],
        );

        if ($includeTracksCount) {
            $mapped['tracks_count'] = $this->tracksCountForRoom($room);
        }

        return $mapped;
    }

    private function tracksCountForRoom(Room $room): int
    {
        return (int) Cache::flexible(
            "room_{$room->id}_tracks_count",
            [300, 900],
            fn () => $room->tracks()->count(),
        );
    }

    /**
     * @param  list<array<string, mixed>>  $rooms
     * @return list<array<string, mixed>>
     */
    private function applyTracksCountsToPayload(array $rooms): array
    {
        if ($rooms === []) {
            return [];
        }

        $roomModels = Room::query()
            ->whereIn('id', array_column($rooms, 'id'))
            ->get()
            ->keyBy('id');

        return array_map(function (array $room) use ($roomModels): array {
            $roomModel = $roomModels->get($room['id']);

            if ($roomModel) {
                $room['tracks_count'] = $this->tracksCountForRoom($roomModel);
            }

            return $room;
        }, $rooms);
    }

    /**
     * @return array<int, int>
     */
    private function publicRoomPresenceCounts(): array
    {
        if ($this->memoizedPublicPresenceCounts !== null) {
            return $this->memoizedPublicPresenceCounts;
        }

        $this->memoizedPublicPresenceCounts = $this->roomPresence->getMemberCountsForRooms(
            collect($this->cachedPublicRooms()),
        );

        return $this->memoizedPublicPresenceCounts;
    }

    /**
     * @param  list<array<string, mixed>>  $rooms
     * @return list<array<string, mixed>>
     */
    private function applyPresenceCounts(array $rooms): array
    {
        if ($rooms === []) {
            return [];
        }

        $counts = $this->roomPresence->getMemberCountsForRooms(collect($rooms));

        return array_map(
            fn (array $room) => array_merge($room, [
                'subscriptions' => $counts[$room['id']] ?? 0,
            ]),
            $rooms,
        );
    }

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
     * @param  list<array<string, mixed>>  $rooms
     * @return list<array<string, mixed>>
     */
    private function sortRoomsByPopularity(array $rooms): array
    {
        return collect($rooms)
            ->sortByDesc(fn (array $room) => [
                (int) ($room['subscriptions'] ?? 0),
                (int) ($room['rounds_count'] ?? 0),
            ])
            ->values()
            ->all();
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
     * @return list<array<string, mixed>>
     */
    public function officialRoomsForCategory(int $categoryId): array
    {
        return collect($this->cachedPublicRooms())
            ->filter(fn (array $room) => ($room['category']['id'] ?? null) === $categoryId)
            ->values()
            ->all();
    }

    /**
     * @return list<array{id: int, name: string, slug: string, rooms_count: int}>
     */
    private function categoriesWithRoomCounts(bool $isPublic): array
    {
        return Category::query()
            ->whereHas('rooms', function ($query) use ($isPublic) {
                $query->whereNull('password');

                if ($isPublic) {
                    $query->isPublic();
                } else {
                    $query->isPrivate();
                }
            })
            ->withCount(['rooms' => function ($query) use ($isPublic) {
                $query->whereNull('password');

                if ($isPublic) {
                    $query->isPublic();
                } else {
                    $query->isPrivate();
                }
            }])
            ->orderBy('name')
            ->get(['id', 'name', 'slug'])
            ->map(fn (Category $category) => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'rooms_count' => $category->rooms_count,
            ])
            ->values()
            ->all();
    }
}
