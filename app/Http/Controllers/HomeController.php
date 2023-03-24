<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Room;
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

        $topRooms = Room::isPublic()
            ->withCount('rounds')
            ->orderByDesc('rounds_count')
            ->limit(5)
            ->get();

        return Inertia::render('Home/Index', [
            'filters' => Request::all('search'),
            'top_rooms' => $topRooms,
            'categories' => Category::all()->map(fn ($category) => [
                'id' => $category->id,
                'name' => $category->name,
                'rooms' => $category->rooms()
                    ->isPublic()
                    ->whereNotIn('id', $topRooms->pluck('id'))
                    ->whereNull('password')
                    ->filter(Request::only('search'))
                    ->orderByDesc('is_playing')
                    ->limit(18)
                    ->get()
                    ->sortByDesc(function ($room) {
                        return $room->users_count;
                    }),
            ]),
            'private_rooms' => Room::isPrivate()
                ->whereNull('password')
                ->filter(Request::only('search'))
                ->with('owner')
                ->orderByDesc('is_playing')
                ->limit(18)
                ->get()
                ->sortByDesc(function ($room) {
                    return $room->users_count;
                }),
            'user_rooms' => auth()->user()
                ? auth()->user()->moderatedRooms()
                    ->isPrivate()
                    ->with('owner')
                    ->filter(Request::only('search'))
                    ->get()
                : null,
        ]);
    }
}
