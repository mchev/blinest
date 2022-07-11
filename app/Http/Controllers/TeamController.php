<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TeamController extends Controller
{
    public function index()
    {
        if (Auth::user()->hasTeam()) {
            return redirect()->route('teams.show', Auth::user()->team->id);
        }

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

    public function create()
    {
        return Auth::user()->hasTeam()
            ? redirect()->back()->with('error', 'Tu est déjà dans une team.')
            : Inertia::render('Teams/Create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->hasTeam()) {
            redirect()->back()->with('error', 'Tu est déjà dans une team.');
        }

        $request->validate([
            'name' => ['required', 'max:30', 'unique:teams'],
        ]);

        $team = Team::create([
            'user_id' => Auth::user()->id,
            'name' => $request->name,
        ]);

        Auth::user()->update([
            'team_id' => $team->id,
        ]);

        return redirect()->route('teams.show', $team);
    }

    public function show(Team $team)
    {
        return Inertia::render('Teams/Show', [
            'team' => $team,
            'score' => $team->scores()->sum('score'),
            'members' => $team->members->map(fn ($member) => [
                'id' => $member->id,
                'name' => $member->name,
                'photo' => $member->photo,
                'score' => $member->scores()->where('team_id', $team->id)->sum('score'),
            ])->sortBy('score'),
        ]);
    }

    public function leave(Team $team)
    {
        if (Auth::user()->id === $team->user_id) {
            return redirect()->back()->with('error', "Impossible de quitter ta propre team, tu dois d'abord donner les droits à un autre membre.");
        }

        Auth::user()->update([
            'team_id' => null,
        ]);

        return redirect()->back()->with('success', 'Tu ne fais maintenant plus parti de la team '.$team->name);
    }
}
