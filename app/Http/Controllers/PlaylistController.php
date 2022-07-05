<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
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
                    'owner' => [
                        'id' => $playlist->owner->id,
                        'name' => $playlist->owner->name,
                    ],
                    'moderators' => $playlist->moderators->map(fn ($moderator) => [
                        'id' => $moderator->id,
                        'name' => $moderator->name,
                    ]),
                    'tracks_count' => $playlist->tracks()->count(),
                    'is_public' => $playlist->isPublic(),
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
        ]);

        $playlist = Auth::user()->playlists()->create([
            'name' => Request::get('name'),
            'is_public' => Request::get('is_public'),
        ]);

        $playlist->moderators()->attach(Auth::user());

        return Redirect::route('playlists.edit', $playlist)->with('success', 'Playlist created.');
    }

    public function edit(Playlist $playlist)
    {
        if (Auth::user()->id === $playlist->owner->id || Auth::user()->isAdministrator()) {
            return Inertia::render('Playlists/Edit', [
                'playlist' => [
                    'id' => $playlist->id,
                    'name' => $playlist->name,
                    'desription' => $playlist->desription,
                    'is_public' => $playlist->is_public,
                    'user_id' => $playlist->user_id,
                    'deleted_at' => $playlist->deleted_at,
                    'owner' => $playlist->owner,
                    'moderators' => $playlist->moderators,
                    'rooms' => $playlist->rooms->map(fn ($room) => [
                        'id' => $room->id,
                        'name' => $room->name,
                    ]),
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
                        //'up_votes' => $track->upVoters()->count(),
                        //'down_votes' => $track->downVoters()->count(),
                        'created_at' => $track->created_at->format('d/m/Y'),
                    ]),
            ]);
        }

        return abort(403, 'Unauthorized action.');
    }

    public function update(Playlist $playlist)
    {
        if (Auth::user()->id === $playlist->owner->id || Auth::user()->isAdministrator()) {
            Request::validate([
                'name' => ['required', 'max:50'],
                'description' => ['nullable'],
                'user_id' => ['required', 'integer', 'exists:users,id'],
            ]);

            $playlist->update(Request::only('name', 'description', 'user_id'));

            return Redirect::back()->with('success', 'Playlist updated.');
        }

        return abort(403, 'Unauthorized action.');
    }

    public function destroy(Playlist $playlist)
    {
        if (Auth::user()->id === $playlist->owner->id || Auth::user()->isAdministrator()) {
            $playlist->delete();

            return Redirect::back()->with('success', 'Playlist deleted.');
        }

        return abort(403, 'Unauthorized action.');
    }

    public function restore(Playlist $playlist)
    {
        if (Auth::user()->id === $playlist->owner->id || Auth::user()->isAdministrator()) {
            $playlist->restore();

            return Redirect::back()->with('success', 'Playlist restored.');
        }

        return abort(403, 'Unauthorized action.');
    }
}
