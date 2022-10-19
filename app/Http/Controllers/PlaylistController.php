<?php

namespace App\Http\Controllers;

use App\Models\AnswerType;
use App\Models\Playlist;
use App\Rules\Reserved;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class PlaylistController extends Controller
{
    public function index()
    {
        if (! Auth::user()->allPlaylists()->count()) {
            return redirect()->route('playlists.create');
        }

        return Inertia::render('Playlists/Index', [
            'filters' => Request::all('search', 'trashed'),
            'playlists' => Auth::user()->allPlaylists()
                ->orderBy('updated_at')
                ->filter(Request::only('search', 'trashed'))
                ->with('moderators', 'owner')
                ->paginate(5)
                ->withQueryString()
                ->through(fn ($playlist) => [
                    'id' => $playlist->id,
                    'name' => $playlist->name,
                    'description' => $playlist->description,
                    'owner' => $playlist->owner,
                    'moderators' => $playlist->moderators->map(fn ($moderator) => [
                        'id' => $moderator->id,
                        'name' => $moderator->name,
                    ]),
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
            'name' => ['required', 'max:50', new Reserved, 'unique:playlists'],
        ]);

        $playlist = Auth::user()->playlists()->create([
            'name' => Request::get('name'),
        ]);

        $playlist->moderators()->attach(Auth::user());

        return Redirect::route('playlists.edit', $playlist)->with('success', 'Playlist created.');
    }

    public function edit(Playlist $playlist)
    {
        if (Auth::user()->id === $playlist->owner->id
                || Auth::user()->isPlaylistModerator($playlist)
                || Auth::user()->isAdministrator()
            ) {
            return Inertia::render('Playlists/Edit', [
                'playlist' => [
                    'id' => $playlist->id,
                    'name' => $playlist->name,
                    'desription' => $playlist->desription,
                    'deleted_at' => $playlist->deleted_at,
                    'user_id' => $playlist->user_id,
                    'moderators' => $playlist->moderators,
                    'rooms' => $playlist->rooms->map(fn ($room) => [
                        'id' => $room->id,
                        'name' => $room->name,
                    ]),
                ],
                'filters' => Request::all('search'),
                'answer_types' => AnswerType::all(),
                'tracks' => $playlist->tracks()
                    ->filter(Request::only('search', 'sortable'))
                    ->with('answers')
                    ->paginate(Request::get('paginate') ?? 5)
                    ->withQueryString()
                    ->through(fn ($track) => [
                        'id' => $track->id,
                        'provider' => $track->provider,
                        'preview_url' => $track->preview_url,
                        'answers' => $track->answers,
                        'dificulty' => $track->dificulty,
                        'up_votes' => $track->upVoters()->count(),
                        'down_votes' => $track->downVoters()->count(),
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
                'name' => ['required', 'max:50', new Reserved, Rule::unique('playlists')->ignore($playlist->id)],
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
            if ($playlist->is_public && $playlist->has('rooms')) {
                return Redirect::back()->with('error', 'Impossible de supprimer une playlist qui est publique et qui est associée à une room.');
            }

            $playlist->moderators()->delete();
            $playlist->tracks()->delete();
            $playlist->delete();

            return Redirect::back()->with('success', 'Playlist deleted.');
        }

        return abort(403, 'Unauthorized action.');
    }
}
