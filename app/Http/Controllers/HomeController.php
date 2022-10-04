<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        return Inertia::render('Home/Index', [
            'filters' => Request::all('search'),
            'categories' => Category::all()->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'rooms' => $category->rooms()
                        ->whereHas('playlists')
                        ->whereNull('password')
                        ->filter(Request::only('search'))
                        ->withCount('rounds')
                        ->orderByDesc('is_public')
                        ->orderByDesc('rounds_count')
                        ->paginate(5, ['*'], 'cat'.$category->id)
                        ->withQueryString(),
                ];
            }),
            'private_rooms' => Auth::user() ? Auth::user()->rooms()
                ->with('owner')
                ->where('is_public', false)
                ->whereHas('playlists')
                ->filter(Request::only('search'))
                ->withCount('rounds')
                ->orderByDesc('rounds_count')
                ->paginate(5, ['*'], 'private')
                ->withQueryString() : null,
        ]);
    }
}
