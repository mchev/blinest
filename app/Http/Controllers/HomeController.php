<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Room;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        return Inertia::render('Home/Index', [
            'categories' => Category::with('publicRooms')->get(),
        ]);
    }
}
