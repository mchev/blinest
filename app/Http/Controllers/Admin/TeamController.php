<?php

namespace App\Http\Controllers\Admin;

use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
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
                ->paginate(5)
                ->withQueryString(),
        ]);
    }

    public function create()
    {
        if (Auth::user()->ownsTeam()) {
            return redirect()->back()->with('error', __('You already own a team'));
        }

        return Inertia::render('Admin/Teams/Create');
    }

    public function store()
    {
        Request::validate([
            'name' => ['required', 'max:50', Rule::unique('teams')->whereNull('deleted_at')],
        ]);

        $team = Auth::user()->team()->create([
            'name' => Request::get('name'),
        ]);

        Auth::user()->update([
            'team_id' => $team->id,
        ]);

        return Redirect::route('admin.teams')->with('success', __('Team created'));
    }

    public function edit(Team $team)
    {
        return Inertia::render('Admin/Teams/Edit', [
            'team' => [
                'id' => $team->id,
                'name' => $team->name,
                'user_id' => $team->user_id,
                'photo' => $team->photo,
                'members' => $team->members,
                'deleted_at' => $team->deleted_at,
            ],
        ]);
    }

    public function update(Team $team)
    {
        Request::validate([
            'name' => ['required', 'max:50', Rule::unique('teams')->ignore($team->id)->whereNull('deleted_at')],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'photo' => ['nullable', 'image'],
        ]);

        $team->update(Request::only('name', 'user_id'));

        if (Request::file('photo')) {
            $team->updatePhoto(Request::file('photo'));
        }

        return Redirect::back()->with('success', __('Team updated'));
    }

    public function destroy(Team $team)
    {
        $team->deletePhoto();
        $team->delete();

        return Redirect::back()->with('success', __('Team deleted'));
    }

    public function restore(Team $team)
    {
        $team->restore();

        return Redirect::back()->with('success', __('Team restored'));
    }
}
