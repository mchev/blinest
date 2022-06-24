<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        return Inertia::render('Home/Index', [
            'categories' => Category::with('publicRooms')->get(),
            'private_rooms' => Auth::user()->rooms()->get('id', 'name'),
        ]);
    }
}
