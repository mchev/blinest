<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Rules\Reserved;
use Illuminate\Http\Request;
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

    public function create()
    {
        return Auth::user()->hasTeam()
            ? redirect()->back()->with('error', 'Tu est déjà dans une team.')
            : Inertia::render('Teams/Create');
    }

    public function switchOwner(Team $team, User $user)
    {
        if ($team->owner->id === Auth::user()->id) {
            $team->update([
                'user_id' => $user->id,
            ]);
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->hasTeam()) {
            redirect()->back()->with('error', 'Tu est déjà dans une team.');
        }

        $request->validate([
            'name' => ['required', 'max:30', 'unique:teams', 'alpha_dash', new Reserved],
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
            'score' => floatval($team->scores()->sum('score')),
            'members' => $team->members->map(fn ($member) => [
                'id' => $member->id,
                'name' => $member->name,
                'photo' => $member->photo,
                'score' => $member->scores()->where('team_id', $team->id)->sum('score'),
            ])->sortBy('score'),
            'user' => [
                'id' => Auth::user()->id,
                'team' => Auth::user()->team,
                'pending_requests' => Auth::user()->teamRequests()->whereNull('declined_at')->pluck('team_id'),
                'declined_requests' => Auth::user()->teamRequests()->whereNotNull('declined_at')->pluck('team_id'),
            ],
        ]);
    }

    public function leave(Team $team)
    {
        if (Auth::user()->id === $team->user_id) {
            return redirect()->back()->with('error', "Impossible de quitter ta propre team, tu dois d'abord donner les droits à un autre membre.");
        }

        if ($team->members()->where('id', Auth::user()->id)->exists()) {
            Auth::user()->update([
                'team_id' => null,
            ]);

            return redirect()->route('teams.index')->with('success', 'Tu ne fais maintenant plus parti de la team '.$team->name);
        } else {
            return redirect()->back()->with('error', 'Tu ne fais pas parti de cette team.');
        }
    }
}
