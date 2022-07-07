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
            'teams' => Team::orderBy('created_at', 'DESC')
                ->with('owner')
                ->withCount('members')
                ->paginate(4),
            'user' => [
                'id' => Auth::user()->id,
                'team' => Auth::user()->team,
                'pending_requests' => Auth::user()->teamRequests()->whereNull('declined_at')->pluck('team_id'),
                'declined_requests' => Auth::user()->teamRequests()->whereNotNull('declined_at')->pluck('team_id'),
            ],
        ]);
    }
}
