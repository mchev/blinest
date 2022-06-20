<?php

namespace App\Http\Controllers\Admin;

use App\Models\Team;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class TeamController extends AdminController
{
    public function index()
    {
        return Inertia::render('Admin/Teams/Index', [
            'filters' => Request::all('search', 'trashed'),
            'teams' => Team::orderBy('updated_at')
                ->filter(Request::only('search', 'trashed'))
                ->withCount('members')
                ->paginate(10)
                ->withQueryString(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Teams/Create');
    }

    public function store()
    {
        Request::validate([
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('teams')],
            'password' => ['nullable'],
            'owner' => ['required', 'boolean'],
            'photo' => ['nullable', 'image'],
        ]);

        Auth::team()->account->teams()->create([
            'first_name' => Request::get('first_name'),
            'last_name' => Request::get('last_name'),
            'email' => Request::get('email'),
            'password' => Request::get('password'),
            'owner' => Request::get('owner'),
            'photo_path' => Request::file('photo') ? Request::file('photo')->store('teams') : null,
        ]);

        return Redirect::route('admin.teams')->with('success', 'Team created.');
    }

    public function edit(Team $team)
    {
        return Inertia::render('Admin/Teams/Edit', [
            'team' => [
                'id' => $team->id,
                'first_name' => $team->first_name,
                'last_name' => $team->last_name,
                'email' => $team->email,
                'owner' => $team->owner,
                'photo' => $team->photo_path ? URL::route('image', ['path' => $team->photo_path, 'w' => 60, 'h' => 60, 'fit' => 'crop']) : null,
                'deleted_at' => $team->deleted_at,
            ],
        ]);
    }

    public function update(Team $team)
    {
        if (App::environment('demo') && $team->isDemoTeam()) {
            return Redirect::back()->with('error', 'Updating the demo team is not allowed.');
        }

        Request::validate([
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('teams')->ignore($team->id)],
            'password' => ['nullable'],
            'owner' => ['required', 'boolean'],
            'photo' => ['nullable', 'image'],
        ]);

        $team->update(Request::only('first_name', 'last_name', 'email', 'owner'));

        if (Request::file('photo')) {
            $team->update(['photo_path' => Request::file('photo')->store('teams')]);
        }

        if (Request::get('password')) {
            $team->update(['password' => Request::get('password')]);
        }

        return Redirect::back()->with('success', 'Team updated.');
    }

    public function destroy(Team $team)
    {
        if (App::environment('demo') && $team->isDemoTeam()) {
            return Redirect::back()->with('error', 'Deleting the demo team is not allowed.');
        }

        $team->delete();

        return Redirect::back()->with('success', 'Team deleted.');
    }

    public function restore(Team $team)
    {
        $team->restore();

        return Redirect::back()->with('success', 'Team restored.');
    }
}
