<?php

namespace App\Http\Controllers\Admin;

use App\Models\Playlist;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class PlaylistController extends AdminController
{
    public function index()
    {
        return Inertia::render('Admin/Playlists/Index', [
            'filters' => Request::all('search', 'trashed'),
            'playlists' => Playlist::orderBy('updated_at')
                ->filter(Request::only('search', 'trashed'))
                ->get()
                ->transform(fn ($playlist) => [
                    'id' => $playlist->id,
                    'name' => $playlist->name,
                    'owner' => $playlist->owner,
                    'photo' => $playlist->photo_path ? URL::route('image', ['path' => $playlist->photo_path, 'w' => 40, 'h' => 40, 'fit' => 'crop']) : null,
                    'deleted_at' => $playlist->deleted_at,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Playlists/Create');
    }

    public function store()
    {
        Request::validate([
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('playlists')],
            'password' => ['nullable'],
            'owner' => ['required', 'boolean'],
            'photo' => ['nullable', 'image'],
        ]);

        Auth::playlist()->account->playlists()->create([
            'first_name' => Request::get('first_name'),
            'last_name' => Request::get('last_name'),
            'email' => Request::get('email'),
            'password' => Request::get('password'),
            'owner' => Request::get('owner'),
            'photo_path' => Request::file('photo') ? Request::file('photo')->store('playlists') : null,
        ]);

        return Redirect::route('admin.playlists')->with('success', 'Playlist created.');
    }

    public function edit(Playlist $playlist)
    {
        return Inertia::render('Admin/Playlists/Edit', [
            'playlist' => [
                'id' => $playlist->id,
                'first_name' => $playlist->first_name,
                'last_name' => $playlist->last_name,
                'email' => $playlist->email,
                'owner' => $playlist->owner,
                'photo' => $playlist->photo_path ? URL::route('image', ['path' => $playlist->photo_path, 'w' => 60, 'h' => 60, 'fit' => 'crop']) : null,
                'deleted_at' => $playlist->deleted_at,
            ],
        ]);
    }

    public function update(Playlist $playlist)
    {
        if (App::environment('demo') && $playlist->isDemoPlaylist()) {
            return Redirect::back()->with('error', 'Updating the demo playlist is not allowed.');
        }

        Request::validate([
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('playlists')->ignore($playlist->id)],
            'password' => ['nullable'],
            'owner' => ['required', 'boolean'],
            'photo' => ['nullable', 'image'],
        ]);

        $playlist->update(Request::only('first_name', 'last_name', 'email', 'owner'));

        if (Request::file('photo')) {
            $playlist->update(['photo_path' => Request::file('photo')->store('playlists')]);
        }

        if (Request::get('password')) {
            $playlist->update(['password' => Request::get('password')]);
        }

        return Redirect::back()->with('success', 'Playlist updated.');
    }

    public function destroy(Playlist $playlist)
    {
        if (App::environment('demo') && $playlist->isDemoPlaylist()) {
            return Redirect::back()->with('error', 'Deleting the demo playlist is not allowed.');
        }

        $playlist->delete();

        return Redirect::back()->with('success', 'Playlist deleted.');
    }

    public function restore(Playlist $playlist)
    {
        $playlist->restore();

        return Redirect::back()->with('success', 'Playlist restored.');
    }
}
