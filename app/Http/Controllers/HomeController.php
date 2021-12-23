<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        return Inertia::render('Home/Index', [
            'public_rooms' => Room::orderBy('name')
                        ->isPublic()
                        ->get(),
        ]);
    }
}
