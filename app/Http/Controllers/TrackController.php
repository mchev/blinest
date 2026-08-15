<?php

namespace App\Http\Controllers;

use App\Events\TrackVoted;
use App\Jobs\ProcessDeletedTrack;
use App\Jobs\SendDiscordNotification;
use App\Jobs\UpdateUserLevel;
use App\Models\Playlist;
use App\Models\Room;
use App\Models\Track;
use App\Rules\AudioDuration;
use App\Services\MusicProvidersService as MusicProviders;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

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
        $user = Request::user();

        if ($user->id === $playlist->owner->id
                || $user->isPlaylistModerator($playlist)
                || $user->isAdministrator()
        ) {
            $searchResponse = (new MusicProviders)->search(
                $user,
                Request::get('term'),
                Request::get('providers')
            );

            return response()->json([
                'filters' => Request::only('term', 'providers'),
                'tracks' => $searchResponse['results']->map(function ($track) use ($playlist) {
                    return [
                        'provider' => $track['provider'],
                        'provider_id' => $track['provider_id'],
                        'provider_url' => $track['provider_url'],
                        'provider_popularity' => $track['provider_popularity'],
                        'artist_name' => $track['artist_name'],
                        'track_name' => $track['track_name'],
                        'album_name' => $track['album_name'],
                        'preview_url' => $track['preview_url'],
                        'artwork_url' => $track['artwork_url'],
                        'added' => $playlist->hasProviderTrack($track['provider_id'])->select('id')->first(),
                    ];
                }),
                'errors' => $searchResponse['errors'],
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
                'provider_url' => ['required', 'max:255'],
                'artist_name' => ['required', 'max:255'],
                'track_name' => ['required', 'max:255'],
                'album_name' => ['nullable', 'max:255'],
                'preview_url' => ['required', 'max:2048'],
                // 'release_date' => ['nullable', 'date'],
                'artwork_url' => ['required', 'max:255'],
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

            // Invalidate cache
            Cache::forget('playlist_total_tracks_'.$playlist->id);
            Cache::forget('playlist_difficulties_'.$playlist->id);

            // Invalidate tracks_count cache for all rooms using this playlist
            foreach ($playlist->rooms()->pluck('id') as $roomId) {
                Cache::forget("room_{$roomId}_tracks_count");
            }

            return Redirect::back()->with('success', __('Track added'));
        }
    }

    public function update(Playlist $playlist, Track $track)
    {
        if (Auth::user()->id === $playlist->owner->id
                || Auth::user()->isPlaylistModerator($playlist)
                || Auth::user()->isAdministrator()
        ) {
            Request::validate([
                'dificulty' => ['nullable', 'integer', 'min:0', 'max:5'],
                'hint' => ['nullable', 'string', 'max:255'],
            ]);

            $updateData = [];
            if (Request::has('dificulty')) {
                $updateData['dificulty'] = Request::get('dificulty');
            }
            if (Request::has('hint')) {
                $updateData['hint'] = Request::get('hint');
            }

            // TRACK
            if (! empty($updateData)) {
                $track->update($updateData);

                // Invalidate cache if difficulty changed
                if (Request::has('dificulty')) {
                    Cache::forget('playlist_difficulties_'.$playlist->id);
                }

                return redirect()->back()->with('success', __('Track updated'));
            }

            return redirect()->back();
        }
    }

    public function destroy(Playlist $playlist, Track $track)
    {
        $user = Request::user();

        if ($user->id === $playlist->owner->id
                || $user->isPlaylistModerator($playlist)
                || $user->isAdministrator()
        ) {

            ProcessDeletedTrack::dispatch($track, $user);

            // Invalidate cache
            Cache::forget('playlist_total_tracks_'.$playlist->id);
            Cache::forget('playlist_difficulties_'.$playlist->id);

            // Invalidate tracks_count cache for all rooms using this playlist
            foreach ($playlist->rooms()->pluck('id') as $roomId) {
                Cache::forget("room_{$roomId}_tracks_count");
            }

            return Redirect::back()->with('success', __('Track deleted'));
        }
    }

    public function destroyAll(Playlist $playlist)
    {
        $user = Request::user();

        if ($user->id !== $playlist->owner->id
            && ! $user->isPlaylistModerator($playlist)
            && ! $user->isAdministrator()
        ) {
            abort(403, __('Unauthorized action'));
        }

        $tracks = $playlist->tracks()->get();

        if ($tracks->isEmpty()) {
            return Redirect::back()->with('info', __('Playlist is already empty'));
        }

        foreach ($tracks as $track) {
            Cache::forget(Track::answersCacheKey($track->id));
            Cache::forget('track_'.$track->id.'_duration');
            ProcessDeletedTrack::dispatch($track, $user);
        }

        Cache::forget('playlist_total_tracks_'.$playlist->id);
        Cache::forget('playlist_difficulties_'.$playlist->id);

        foreach ($playlist->rooms()->pluck('id') as $roomId) {
            Cache::forget("room_{$roomId}_tracks_count");
        }

        return Redirect::back()->with('success', __('All tracks removed from the playlist'));
    }

    public function downvote(Room $room, Track $track)
    {
        Auth::user()->downvote($track);
        broadcast(new TrackVoted($room, $track));

        // Update user level in queue when unliking a track
        UpdateUserLevel::dispatch(
            user: Auth::user(),
            type: 'likes_count'
        );
    }

    public function upvote(Room $room, Track $track)
    {
        Auth::user()->upvote($track);
        broadcast(new TrackVoted($room, $track));

        // Update user level in queue when liking a track
        UpdateUserLevel::dispatch(
            user: Auth::user(),
            type: 'likes_count'
        );
    }

    public function upload(Playlist $playlist)
    {
        $validated = Request::validate([
            'artist_name' => ['required', 'max:255'],
            'track_name' => ['required', 'max:255'],
            'audio' => ['required', 'file', 'mimes:mp3', 'max:1024', new AudioDuration(30)],
            'artwork' => ['required', 'file', 'mimes:png,jpg,jpeg', 'max:512'],
        ]);

        try {
            // Upload audio file
            $audio_path = Storage::disk('ovh')->put('uploads', $validated['audio']);
            $audio_url = config('filesystems.disks.ovh.url').'/'.$audio_path;

            // Upload artwork file
            $artwork_path = Storage::disk('ovh')->put('artworks', $validated['artwork']);
            $artwork_url = config('filesystems.disks.ovh.url').'/'.$artwork_path;
        } catch (\Exception $e) {
            Log::error('Error uploading file: '.$e->getMessage());

            return Redirect::back()->withError('Error uploading file');
        }

        // Create track record
        $track = Track::create([
            'playlist_id' => $playlist->id,
            'preview_url' => $audio_path,
            'artwork_url' => $artwork_path,
            'provider' => 'local',
            'provider_id' => $validated['audio']->hashName(),
            'provider_url' => $audio_url,
            'user_id' => auth()->id(), // Track the user who uploaded
        ]);

        // Create answer records for the track
        $track->answers()->createMany([
            [
                'answer_type_id' => 1, // Artist
                'value' => $validated['artist_name'],
            ],
            [
                'answer_type_id' => 2, // Title
                'value' => $validated['track_name'],
            ],
        ]);

        return Redirect::back()->withSuccess('Track successfully added');
    }
}
