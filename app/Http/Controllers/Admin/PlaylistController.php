<?php

namespace App\Http\Controllers\Admin;

use App\Models\AnswerType;
use App\Models\Playlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class PlaylistController extends AdminController
{
    public function index()
    {
        return Inertia::render('Admin/Playlists/Index', [
            'filters' => Request::all('search', 'trashed'),
            'playlists' => Playlist::orderBy('updated_at')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($playlist) => [
                    'id' => $playlist->id,
                    'name' => $playlist->name,
                    'description' => $playlist->description,
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
        return Inertia::render('Admin/Playlists/Create');
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

        return Redirect::route('admin.playlists.edit', $playlist)->with('success', 'Playlist created.');
    }

    public function edit(Playlist $playlist)
    {
        return Inertia::render('Admin/Playlists/Edit', [
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
            'answer_types' => AnswerType::all(),
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

    public function update(Playlist $playlist)
    {
        Request::validate([
            'name' => ['required', 'max:50'],
            'description' => ['nullable'],
            'is_public' => ['required', 'boolean'],
        ]);

        $playlist->update(Request::only('name', 'description', 'is_public'));

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
