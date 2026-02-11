<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Minigames\MinigameController;
use App\Models\Category;
use App\Models\MinigameScore;
use App\Models\Room;
use App\Services\Minigames\MinigameScoreService;
use App\Services\RoomPresenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class HomeController extends Controller
{
    /**
     * Add real-time member count from Redis (RoomPresenceService) to each room.
     * Uses a single Redis pipeline for all rooms to reduce round-trips.
     */
    private function withPresenceCount(Collection $rooms): Collection
    {
        if ($rooms->isEmpty()) {
            return $rooms->map(fn (Room $room) => array_merge($room->toArray(), ['subscriptions' => 0]));
        }

        $roomPresence = app(RoomPresenceService::class);
        $counts = $roomPresence->getMemberCountsForRooms($rooms);

        return $rooms->map(fn (Room $room) => array_merge($room->toArray(), [
            'subscriptions' => $counts[$room->id] ?? 0,
        ]));
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
                'search_result' => $this->withPresenceCount($searchResult)->values()->all(),
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
            'featured_rooms' => $this->withPresenceCount(Room::where('is_featured', true)->get())->values()->all(),
            'categories' => $categories->map(fn ($category) => [
                'id' => $category->id,
                'name' => $category->name,
                'rooms' => $this->withPresenceCount($category->rooms)->values()->all(),
            ]),
            'private_rooms' => $this->withPresenceCount(
                Room::isPrivate()
                    ->whereNull('password')
                    ->with('owner')
                    ->orderByDesc('is_playing')
                    ->limit(18)
                    ->get()
            )->values()->all(),
            'user_rooms' => $user
                ? $this->withPresenceCount(
                    Cache::remember('homepage-moderatedrooms-'.$user->id, now()->addDay(), function () use ($user) {
                        return $user->moderatedRooms()
                            ->isPrivate()
                            ->with('owner')
                            ->get();
                    })
                )->values()->all()
                : null,
        ]);
    }
}
