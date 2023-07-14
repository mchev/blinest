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
                $query->notBanned();
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
            ? redirect()->back()->with('error', __('You are already part of a team'))
            : Inertia::render('Teams/Create');
    }

    public function switchOwner(Team $team, User $user)
    {
        if ($team?->owner?->id === Auth::user()->id) {
            $team->update([
                'user_id' => $user->id,
            ]);

            return redirect()->route('teams.show', $team)->with('success', __('The owner of the team has been updated'));
        }

        return abort(403, __('Unauthorized action'));
    }

    public function removeMember(Team $team, User $user)
    {
        if ($team?->owner?->id === Auth::user()->id) {
            $user->team_id = null;
            $user->update();

            return redirect()->route('teams.show', $team)->with('success', __('The member is not part of the team anymore'));
        }

        return abort(403, __('Unauthorized action'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->hasTeam()) {
            redirect()->back()->with('error', __('You are already part of a team'));
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

        return redirect()->back()->with('error', __('This is not your team'));
    }

    public function leave(Team $team)
    {
        if (Auth::user()->id === $team->user_id) {
            return redirect()->back()->with('error', __('Impossible to leave your own team. You must transfer ownership to another member first'));
        }

        if ($team->members()->where('id', Auth::user()->id)->exists()) {
            Auth::user()->update([
                'team_id' => null,
            ]);

            return redirect()->route('teams.index')->with('success', __('You have left the team').' '.$team->name);
        } else {
            return redirect()->back()->with('error', __('You are not part of this team'));
        }
    }

    public function destroy(Team $team)
    {
        if (Auth::user()->id === $team->user_id && $team->members->count() === 1) {
            $team->delete();

            return redirect()->route('teams.index')->with('success', __('The Team').' '.$team->name.' '.__('has been deleted'));
        }

        return redirect()->back()->with('error', __('This is not your team'));
    }
}
