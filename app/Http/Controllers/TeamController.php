<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Rules\Reserved;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Teams/Index', [
            'filters' => $request->all('search'),
            'teams' => Team::whereHas('owner', function ($query) {
                $query->withoutBanned();
            })
                ->orderBy('created_at', 'DESC')
                ->filter($request->only('search'))
                ->with('owner')
                ->withCount('members')
                ->paginate(4),
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
            ])->sortByDesc('score'),
        ]);
    }

    public function update(Request $request, Team $team)
    {
        if (Auth::user()->id === $team->user_id) {
            $request->validate([
                'name' => ['required', 'max:30', Rule::unique('teams')->ignore($team->id), 'alpha_dash', new Reserved],
                'photo' => ['nullable', 'image'],
            ]);

            $team->update($request->only('name'));

            if ($request->file('photo')) {
                $team->updatePhoto($request->file('photo'));
            }

            return redirect()->back()->with('success', __('Updated'));
        }

        return redirect()->back()->with('error', __("Ce n'est pas ta team."));
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
