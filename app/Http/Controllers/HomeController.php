<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        return Inertia::render('Home/Index', [
            'filters' => Request::all('search'),
            'top_rooms' => Room::isPublic()
                ->withCount('rounds')
                ->orderByDesc('rounds_count')
                ->limit(5)
                ->get(),
            'categories' => Category::all()->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'rooms' => $category->rooms()
                        ->isPublic()
                        ->whereHas('playlists')
                        ->whereNull('password')
                        ->filter(Request::only('search'))
                        ->with('owner')
                        ->withCount('rounds')
                        ->paginate(30, ['*'], 'cat'.$category->id)
                        ->withQueryString(),
                ];
            }),
            'private_rooms' => Room::isPrivate()
                ->whereHas('playlists')
                ->whereNull('password')
                ->filter(Request::only('search'))
                ->with('owner')
                ->withCount('rounds')
                ->orderByDesc('is_playing')
                ->orderByDesc('is_public')
                ->orderByDesc('rounds_count')
                ->paginate(30, ['*'], 'private_rooms')
                ->withQueryString(),
            'user_rooms' => Auth::user() ? Auth::user()->rooms()
                ->with('owner')
                ->where('is_public', false)
                ->whereHas('playlists')
                ->filter(Request::only('search'))
                ->withCount('rounds')
                ->orderByDesc('rounds_count')
                ->paginate(30, ['*'], 'user_rooms')
                ->withQueryString() : null,
        ]);
    }
}
