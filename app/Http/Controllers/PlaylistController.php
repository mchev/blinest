<?php

namespace App\Http\Controllers;

use App\Exports\PlaylistExport;
use App\Models\AnswerType;
use App\Models\Playlist;
use App\Rules\Reserved;
use App\Services\MusicProviders\BlinestLikesService;
use App\Services\MusicProviders\DeezerService;
use App\Services\MusicProviders\SpotifyService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class PlaylistController extends Controller
{
    public function index()
    {
        if (! auth()->user()->moderatedPlaylists()->count()) {
            return redirect()->route('playlists.create');
        }

        return Inertia::render('Playlists/Index', [
            'filters' => Request::all('search', 'trashed'),
            'playlists' => auth()->user()->moderatedPlaylists()
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

        $playlist = auth()->user()->playlists()->create([
            'name' => Request::get('name'),
        ]);

        $playlist->moderators()->attach(auth()->user());

        return Redirect::route('playlists.edit', $playlist)->with('success', __('Playlist created'));
    }

    public function edit(Playlist $playlist)
    {
        if (auth()->user()->id === $playlist->owner->id
                || auth()->user()->isPlaylistModerator($playlist)
                || auth()->user()->isAdministrator()
        ) {
            return Inertia::render('Playlists/Edit', [
                'playlist' => [
                    'id' => $playlist->id,
                    'name' => $playlist->name,
                    'description' => $playlist->description,
                    'deleted_at' => $playlist->deleted_at,
                    'user_id' => $playlist->user_id,
                    'moderators' => $playlist->moderators,
                    'rooms' => $playlist->rooms->map(fn ($room) => [
                        'id' => $room->id,
                        'slug' => $room->slug,
                        'name' => $room->name,
                        'photo' => $room->photo,
                        'owner' => $room->owner,
                    ]),
                    'difficulties' => [
                        'Easy' => $playlist->tracks()->where('dificulty', 0)->count(),
                        'Medium' => $playlist->tracks()->where('dificulty', 1)->count(),
                        'Difficult' => $playlist->tracks()->where('dificulty', 2)->count(),
                        'Expert' => $playlist->tracks()->where('dificulty', 3)->count(),
                    ],
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

        return abort(403, __('Unauthorized action'));
    }

    public function update(Playlist $playlist)
    {
        if (auth()->user()->id === $playlist->owner->id || auth()->user()->isAdministrator()) {
            Request::validate([
                'name' => ['required', 'max:50', new Reserved, Rule::unique('playlists')->ignore($playlist->id)],
                'description' => ['nullable'],
                'user_id' => ['required', 'integer', 'exists:users,id'],
            ]);

            $playlist->update(Request::only('name', 'description', 'user_id'));

            return Redirect::back()->with('success', __('Playlist updated'));
        }

        return abort(403, __('Unauthorized action'));
    }

    public function export(Playlist $playlist)
    {
        return Excel::download(new PlaylistExport($playlist), 'playlist.xlsx');
    }

    public function destroy(Playlist $playlist)
    {
        if (auth()->user()->id === $playlist->owner->id || auth()->user()->isAdministrator()) {
            if ($playlist->is_public && $playlist->has('rooms')) {
                return Redirect::back()->with('error', __('Impossible to delete a playlist that is public and associated to a room'));
            }

            $playlist->moderators()->detach();
            $playlist->tracks()->delete();
            $playlist->delete();

            return Redirect::route('playlists')->with('success', __('Playlist deleted'));
        }

        return abort(403, __('Unauthorized action'));
    }

    public function findPlaylistByProvider(Playlist $playlist)
    {
        Request::validate([
            'provider' => ['required'],
            'playlist_id' => 'required_if:provider,Spotify,Deezer|alpha_num|nullable',
        ]);

        if (Request::input('provider') === 'Spotify') {
            $providerPlaylist = (new SpotifyService)->findPlaylistById(Request::input('playlist_id'));
        }

        if (Request::input('provider') === 'Deezer') {
            $providerPlaylist = (new DeezerService)->findPlaylistById(Request::input('playlist_id'));
        }

        if (Request::input('provider') === 'Blinest likes') {
            $providerPlaylist = (new BlinestLikesService)->getLikesInformation();
        }

        return Inertia::render('Playlists/Edit', [
            'providerPlaylist' => $providerPlaylist,
        ]);
    }

    public function importPlaylistFromProvider(Playlist $playlist)
    {
        if (Request::input('provider') === 'Spotify') {
            $count = (new SpotifyService)->importPlaylist($playlist, Request::input('playlist_id'));
        }
        if (Request::input('provider') === 'Deezer') {
            $count = (new DeezerService)->importPlaylist($playlist, Request::input('playlist_id'));
        }
        if (Request::input('provider') === 'Blinest likes') {
            $count = (new BlinestLikesService)->importPlaylist($playlist);
        }

        return redirect()->route('playlists.edit', $playlist)->with('success', $count.'/'.Request::input('tracks_count').' '.__('tracks have been imported'));
    }
}
