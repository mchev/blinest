<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TeamController extends Controller
{
    public function index()
    {
        return Inertia::render('Teams/Index', [
            'teams' => Team::inRandomOrder()
                ->with('owner')
                ->withCount('members')
                ->paginate(4),
            'user' => [
                'id' => Auth::user()->id,
                'team' => Auth::user()->team,
            ],
        ]);
    }

    public function invitation()
    {
    }
}
