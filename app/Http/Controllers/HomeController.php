<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Room;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        if (Request::only('search')) {
            return $this->search(Request::only('search'));
        }

        $topRooms = Cache::remember('toprooms', 3600, function () {
            return Room::isPublic()
                ->withCount('rounds')
                ->orderByDesc('rounds_count')
                ->limit(5)
                ->get();
        });

        return Inertia::render('Home/Index', [
            'filters' => Request::all('search'),
            'top_rooms' => $topRooms,
            'categories' => Cache::remember('categories', 600, function () use ($topRooms) {
                return Category::all()->map(fn ($category) => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'rooms' => $category->rooms()
                        ->isPublic()
                        ->whereNotIn('id', $topRooms->pluck('id'))
                        ->whereHas('playlists')
                        ->whereNull('password')
                        ->filter(Request::only('search'))
                        ->withCount('rounds')
                        ->orderByDesc('is_playing')
                        ->orderByDesc('rounds_count')
                        ->limit(18)
                        ->get(),
                ]);
            }),
            'private_rooms' => Cache::remember('privaterooms', 600, function () {
                return Room::isPrivate()
                    ->whereHas('playlists')
                    ->whereNull('password')
                    ->filter(Request::only('search'))
                    ->with('owner')
                    ->withCount('rounds')
                    ->orderByDesc('is_playing')
                    ->orderByDesc('is_public')
                    ->orderByDesc('rounds_count')
                    ->limit(18)
                    ->get();
            }),
            'user_rooms' => auth()->user() ? auth()->user()->moderatedRooms()
                ->with('owner')
                ->where('is_public', false)
                ->whereHas('playlists')
                ->filter(Request::only('search'))
                ->get() : null,
        ]);
    }

    public function search($search)
    {
        return Inertia::render('Home/Index', [
            'filters' => Request::all('search'),
            'search_result' => Room::query()
                ->whereHas('playlists')
                ->whereNull('password')
                ->filter($search)
                ->with('owner')
                ->withCount('rounds')
                ->orderByDesc('is_playing')
                ->orderByDesc('is_public')
                ->orderByDesc('rounds_count')
                ->limit(30)
                ->get(),
        ]);
    }
}
