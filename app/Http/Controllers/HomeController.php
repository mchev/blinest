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
                $payload = is_array($room) ? $room : $room->toArray();

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
                    $room->toArray(),
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

        $categories = Category::with(['rooms' => function ($query) {
            $query->isPublic()->whereNull('password');
        }])->get();

        $user = $request->user();

        $minigameScores = $user
            ? app(MinigameScoreService::class)->getTotalsByTypeForUser($user)
            : array_combine(array_keys(MinigameScore::typeLabels()), array_fill(0, count(MinigameScore::typeLabels()), 0));

        return Inertia::render('Home/Index', [
            'filters' => $request->all('search'),
            'minigames' => MinigameController::buildGamesList($minigameScores),
            'weekly_top_users' => Cache::get('weekly-top-10-users'),
            'featured_rooms' => $this->withPresenceCount(Room::where('is_featured', true)->get()),
            'categories' => $categories->map(fn ($category) => [
                'id' => $category->id,
                'name' => $category->name,
                'rooms' => $this->withPresenceCount($category->rooms),
            ]),
            'private_rooms' => $this->withPresenceCount(
                Room::isPrivate()
                    ->whereNull('password')
                    ->with('owner')
                    ->orderByDesc('is_playing')
                    ->limit(18)
                    ->get()
            ),
            'user_rooms' => $user
                ? $this->withPresenceCount($this->cachedModeratedRoomsForUser($user))
                : null,
        ]);
    }
}
