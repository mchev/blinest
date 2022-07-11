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
            'categories' => Category::with(['rooms' => function ($query) {
                $query->isPublic()
                    ->whereHas('playlists')
                    ->whereNull('password')
                    ->filter(Request::only('search'));
            }])->get(),
            'private_rooms' => Auth::user()->rooms()->with('owner')
                ->whereHas('playlists')
                ->filter(Request::only('search'))
                ->get(),
        ]);
    }
}
