<?php

namespace App\Http\Controllers;

use App\Events\TrackVoted;
use App\Jobs\SendDiscordNotification;
use App\Models\Playlist;
use App\Models\Room;
use App\Models\Track;
use App\Services\MusicProvidersService as MusicProviders;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class TrackController extends Controller
{
    public function index(Playlist $playlist)
    {
        return Redirect::back()->with([
            'filters' => Request::all('search'),
            'tracks' => $playlist->tracks()
                ->orderBy('track_name')
                ->filter(Request::only('search'))
                ->paginate(10)
                ->withQueryString()
                ->transform(fn ($track) => [
                    'id' => $track->id,
                    'provider' => $track->provider,
                    'provider_url' => $track->provider_url,
                    'answers' => $track->answers,
                    'dificulty' => $track->dificulty,
                    'up_votes' => $track->upVoters()->count(),
                    'down_votes' => $track->downVoters()->count(),
                    'created_at' => $track->created_at->format('d/m/Y'),
                ]),
        ]);
    }

    public function search(Playlist $playlist)
    {
        if (Auth::user()->id === $playlist->owner->id
                || Auth::user()->isPlaylistModerator($playlist)
                || Auth::user()->isAdministrator()
            ) {
            return response()->json([
                'filters' => Request::only('term'),
                'tracks' => (new MusicProviders)->search(Request::get('term'))
                    // ->sortBy('track_name')
                    // ->unique(function ($item) {
                    //     return $item['artist_name'].$item['track_name'];
                    // })
                    ->values()
                    ->map(function ($track) use ($playlist) {
                        return [
                            'provider' => $track['provider'],
                            'provider_id' => $track['provider_id'],
                            'provider_url' => $track['provider_url'],
                            'artist_name' => $track['artist_name'],
                            'track_name' => $track['track_name'],
                            'album_name' => $track['album_name'],
                            'preview_url' => $track['preview_url'],
                            // 'release_date' => $track['release_date'],
                            'artwork_url' => $track['artwork_url'],
                            'added' => $playlist->hasProviderTrack($track['provider_id'])->select('id')->first(),
                        ];
                    }),
            ]);
        }
    }

    public function store(Playlist $playlist)
    {
        if (Auth::user()->id === $playlist->owner->id
                || Auth::user()->isPlaylistModerator($playlist)
                || Auth::user()->isAdministrator()
            ) {

            // VALIDATE
            Request::validate([
                'provider' => ['required', 'max:50'],
                'provider_id' => ['required', 'max:255'],
                'provider_url' => ['required', 'url', 'max:255'],
                'artist_name' => ['required', 'max:255'],
                'track_name' => ['required', 'max:255'],
                'album_name' => ['required', 'max:255'],
                'preview_url' => ['required', 'max:255'],
                // 'release_date' => ['nullable', 'date'],
                'artwork_url' => ['required'],
            ]);

            // TRACK
            $track = $playlist->tracks()->updateOrCreate(
                [
                    'provider' => Request::get('provider'),
                    'provider_id' => Request::get('provider_id'),
                ],
                [
                    'provider_url' => Request::get('provider_url'),
                    // 'artist_name' => Request::get('artist_name'),
                    // 'track_name' => Request::get('track_name'),
                    // 'album_name' => Request::get('album_name'),
                    'preview_url' => Request::get('preview_url'),
                    // 'release_date' => Request::get('release_date'),
                    'artwork_url' => Request::get('artwork_url'),
                ]
            );

            // ANSWERS
            $track->answers()->updateOrCreate(
                ['answer_type_id' => 1], // Artist
                ['value' => Request::get('artist_name')]
            );
            $track->answers()->updateOrCreate(
                ['answer_type_id' => 2], // Title
                ['value' => Request::get('track_name')]
            );

            foreach ($playlist->rooms()->isPublic()->get() as $room) {
                if ($room->discord_webhook_url) {
                    SendDiscordNotification::dispatch(
                        $room,
                        'Le titre '.$track->answers()->where('answer_type_id', 2)->first()?->value.' de '.$track->answers()->where('answer_type_id', 1)->first()?->value.' a été ajouté par '.auth()->user()->name.'.',
                        'success'
                    );
                }
            }

            return Redirect::back()->with('Track added');
        }
    }

    public function update(Playlist $playlist, Track $track)
    {
        if (Auth::user()->id === $playlist->owner->id
                || Auth::user()->isPlaylistModerator($playlist)
                || Auth::user()->isAdministrator()
            ) {
            Request::validate([
                'dificulty' => ['required', 'integer', 'min:0', 'max:5'],
            ]);

            // TRACK
            $track->update([
                'dificulty' => Request::get('dificulty'),
            ]);

            return redirect()->back();
        }
    }

    public function destroy(Playlist $playlist, Track $track)
    {
        if (Auth::user()->id === $playlist->owner->id
                || Auth::user()->isPlaylistModerator($playlist)
                || Auth::user()->isAdministrator()
            ) {
            $track->deleteWithNotification();

            return Redirect::back()->with('Track deleted');
        }
    }

    public function downvote(Room $room, Track $track)
    {
        Auth::user()->downvote($track);
        broadcast(new TrackVoted($room, $track));
    }

    public function upvote(Room $room, Track $track)
    {
        Auth::user()->upvote($track);
        broadcast(new TrackVoted($room, $track));
    }
}
