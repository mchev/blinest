<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Room;
use App\Models\TotalScore;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        if (Request::only('search')) {
            return Inertia::render('Home/Index', [
                'filters' => Request::all('search'),
                'search_result' => Room::query()
                    ->whereHas('playlists')
                    ->whereNull('password')
                    ->filter(Request::only('search'))
                    ->with('owner')
                    ->withCount('rounds')
                    ->orderByDesc('is_playing')
                    ->orderByDesc('is_public')
                    ->orderByDesc('rounds_count')
                    ->limit(30)
                    ->get(),
            ]);
        }

        // $topRooms = Cache::remember('homepage-toprooms', now()->addMinutes(60), function () {
        //     return Room::isPublic()
        //         ->withCount('rounds')
        //         ->orderByDesc('rounds_count')
        //         ->limit(5)
        //         ->get();
        // });

        $categories = Cache::remember('homepage-categories', now()->addMinutes(60), function () {
            return Category::all();
        });

        return Inertia::render('Home/Index', [
            'filters' => Request::all('search'),
            'featured_rooms' => Room::where('is_featured', true)->get(),
            'categories' => $categories->map(fn ($category) => [
                'id' => $category->id,
                'name' => $category->name,
                'rooms' => $category->rooms()
                    ->isPublic()
                    ->whereNull('password')
                    ->orderByDesc('is_playing')
                    ->limit(18)
                    ->get()
                    ->sortByDesc(function ($room) {
                        return $room->users_count;
                    })
                    ->values()
                    ->all(),
            ]),
            'private_rooms' => Room::isPrivate()
                ->whereNull('password')
                ->whereHas('playlists')
                ->with('owner')
                ->orderByDesc('is_playing')
                ->limit(18)
                ->get()
                ->sortByDesc(function ($room) {
                    return $room->users_count;
                })
                ->values()
                ->all(),
            'user_rooms' => auth()->user()
                ? Cache::remember('homepage-moderatedrooms-'.auth()->user()->id, now()->addMinutes(10), function () {
                    return auth()->user()->moderatedRooms()
                        ->isPrivate()
                        ->with('owner')
                        ->get();
                })
                : null,
        ]);
    }
}
