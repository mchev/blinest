<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class PlaylistController extends Controller
{
    public function index()
    {
        return Inertia::render('Playlists/Index', [
            'filters' => Request::all('search', 'trashed'),
            'playlists' => Auth::user()->playlists()
                ->orderBy('updated_at')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($playlist) => [
                    'id' => $playlist->id,
                    'name' => $playlist->name,
                    'owner' => $playlist->owner->name,
                    'is_public' => $playlist->isPublic(),
                    'tracks_count' => $playlist->tracks()->count(),
                    'photo' => $playlist->photo_path ? URL::route('image', ['path' => $playlist->photo_path, 'w' => 40, 'h' => 40, 'fit' => 'crop']) : null,
                    'deleted_at' => $playlist->deleted_at,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Playlists/Create');
    }

    public function store()
    {
        Request::validate([
            'name' => ['required', 'max:50'],
            'is_public' => ['required', 'boolean'],
            'photo' => ['nullable', 'image'],
        ]);

        $playlist = Auth::user()->playlists()->create([
            'name' => Request::get('name'),
            'is_public' => Request::get('is_public'),
            'photo_path' => Request::file('photo') ? Request::file('photo')->store('playlists') : null,
        ]);

        return Redirect::route('playlists.edit', $playlist)->with('success', 'Playlist created.');
    }

    public function edit(Playlist $playlist)
    {
        return Inertia::render('Playlists/Edit', [
            'playlist' => [
                'id' => $playlist->id,
                'name' => $playlist->name,
                'is_public' => $playlist->is_public,
                'user_id' => $playlist->user_id,
                'photo' => $playlist->photo_path ? URL::route('image', ['path' => $playlist->photo_path, 'w' => 60, 'h' => 60, 'fit' => 'crop']) : null,
                'deleted_at' => $playlist->deleted_at,
            ],
            'filters' => Request::all('search'),
            'tracks' => $playlist->tracks()
                ->orderBy('track_name')
                ->filter(Request::only('search'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($track) => [
                    'id' => $track->id,
                    'provider' => $track->provider,
                    'provider_url' => $track->provider_url,
                    'preview_url' => $track->preview_url,
                    'answers' => $track->answers,
                    'up_votes' => $track->upVoters()->count(),
                    'down_votes' => $track->downVoters()->count(),
                    'created_at' => $track->created_at->format('d/m/Y'),
                ]),
        ]);
    }

    public function update(Playlist $playlist)
    {

        Request::validate([
            'name' => ['required', 'max:50'],
            'is_public' => ['required', 'boolean'],
            'photo' => ['nullable', 'image'],
        ]);

        $playlist->update(Request::only('name', 'is_public'));

        if (Request::file('photo')) {
            $playlist->update(['photo_path' => Request::file('photo')->store('playlists')]);
        }

        return Redirect::back()->with('success', 'Playlist updated.');
    }

    public function destroy(Playlist $playlist)
    {

        $playlist->delete();

        return Redirect::back()->with('success', 'Playlist deleted.');
    }

    public function restore(Playlist $playlist)
    {
        $playlist->restore();

        return Redirect::back()->with('success', 'Playlist restored.');
    }
}
