<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->only('search')) {
            return Inertia::render('Home/Index', [
                'filters' => $request->all('search'),
                'search_result' => Room::query()
                    ->whereHas('playlists')
                    ->whereNull('password')
                    ->filter($request->only('search'))
                    ->with('owner')
                    ->withCount('rounds')
                    ->orderByDesc('is_playing')
                    ->orderByDesc('is_public')
                    ->orderByDesc('rounds_count')
                    ->limit(20)
                    ->get(),
            ]);
        }

        $categories = Category::with(['rooms' => function ($query) {
            $query->isPublic()
                ->whereNull('password')
                ->orderByDesc('is_playing');
        }])->get();
        $user = $request->user();

        return Inertia::render('Home/Index', [
            'filters' => $request->all('search'),
            'weekly_top_users' => Cache::get('weekly-top-10-users'),
            'featured_rooms' => Room::where('is_featured', true)->get(),
            'categories' => $categories->map(fn ($category) => [
                'id' => $category->id,
                'name' => $category->name,
                'rooms' => $category->rooms,
            ]),
            'private_rooms' => Room::isPrivate()
                ->whereNull('password')
                ->with('owner')
                ->orderByDesc('is_playing')
                ->limit(18)
                ->get(),
            'user_rooms' => $user
                ? Cache::remember('homepage-moderatedrooms-'.$user->id, now()->addDay(), function () use ($user) {
                    return $user->moderatedRooms()
                        ->isPrivate()
                        ->with('owner')
                        ->get();
                })
                : null,
        ]);
    }
}
