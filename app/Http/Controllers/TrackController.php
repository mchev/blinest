<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\Track;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

use App\Services\MusicProvidersService as MusicProviders;

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
                    'up_votes' => $track->upVoters()->count(),
                    'down_votes' => $track->downVoters()->count(),
                    'created_at' => $track->created_at->format('d/m/Y'),
                ]),
        ]);
    }

    public function search(Playlist $playlist)
    {
        return response()->json([
            'filters' => Request::only('term'),
            'tracks' => (new MusicProviders)->search(Request::get('term'))
                ->sortBy('track_name')->unique(function($item) {
                    return $item['artist_name'].$item['track_name'];
                })
                ->values()
                ->map(function($track) use($playlist) {
                    return [
                        'provider' => $track['provider'],
                        'provider_id' => $track['provider_id'],
                        'provider_url' => $track['provider_url'],
                        'artist_name' => $track['artist_name'],
                        'track_name' => $track['track_name'],
                        'album_name' => $track['album_name'],
                        'preview_url' => $track['preview_url'],
                        'release_date' => $track['release_date'],
                        'artwork_url' => $track['artwork_url'],
                        'added' => $playlist->hasProviderTrack($track['provider_id'])->select('id')->first(),
                    ];
                }),
        ]);
    }


    public function store(Playlist $playlist)
    {

        // VALIDATE
        Request::validate([
            'provider' => ['required', 'max:50'],
            'provider_id' => ['required', 'max:255'],
            'provider_url' => ['required', 'url', 'max:255'],
            'artist_name' => ['required', 'max:255'],
            'track_name' => ['required', 'max:255'],
            'album_name' => ['required', 'max:255'],
            'preview_url' => ['required', 'max:255'],
            'release_date' => ['nullable', 'date'],
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
                'artist_name' => Request::get('artist_name'),
                'track_name' => Request::get('track_name'),
                'album_name' => Request::get('album_name'),
                'preview_url' => Request::get('preview_url'),
                'release_date' => Request::get('release_date'),
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
        if(Request::get('album_name')) {
            $track->answers()->updateOrCreate(
                ['answer_type_id' => 3], // Album
                ['value' => Request::get('album_name')]
            );
        }

        return Redirect::back()->with('Track added');

    }


    public function update(Room $room)
    {
        if (App::environment('demo') && $room->isDemoRoom()) {
            return Redirect::back()->with('error', 'Updating the demo room is not allowed.');
        }

        Request::validate([
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('rooms')->ignore($room->id)],
            'password' => ['nullable'],
            'owner' => ['required', 'boolean'],
            'photo' => ['nullable', 'image'],
        ]);

        $room->update(Request::only('first_name', 'last_name', 'email', 'owner'));

        return Redirect::back()->with('success', 'Room updated.');
    }

    public function destroy(Track $track)
    {
        $track->delete();
        return Redirect::back()->with('Track deleted');
    }

}
