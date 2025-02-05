<?php

namespace App\Services\MusicProviders;

use App\Jobs\ProcessImportTrack;
use App\Models\Playlist;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;

class DeezerService
{
    public function searchTrack()
    {
        $term = Request::get('term');

        $query = urlencode(trim($term));

        $url = 'https://api.deezer.com/search/track?q='.$query.'&strict=on&limit=25';

        $response = Http::get($url);

        if (! $response->successful()) {
            return null;
        }

        $collection = $response->collect();
        $results = $collection['data'] ?? null;

        return $results ? collect($results)
            ->where('readable', true)
            ->map(fn ($track) => $this->formatTrack($track)) : null;
    }

    public function getLiveTrackPreview($id)
    {
        $url = 'https://api.deezer.com/track/'.$id;
        $response = Http::get($url);

        if (! $response->successful()) {
            return null;
        }

        $track = $response->collect();

        return ($track['readable'] ?? false) ? $track['preview'] : null;
    }

    public function getReleaseDate($album)
    {
        $url = 'https://api.deezer.com/album/'.$album;
        $collection = Http::get($url)->collect();

        return Carbon::parse($collection['release_date'])->format('Y-m-d');
    }

    public function findPlaylistById(string $id): object
    {
        $url = 'https://api.deezer.com/playlist/'.$id;
        $playlist = Http::get($url)->object();

        if (isset($playlist->error)) {
            return (object) [
                'code' => $playlist->error->code,
                'message' => $playlist->error->message,
            ];
        }

        return (object) [
            'id' => $playlist->id,
            'name' => $playlist->title,
            'description' => $playlist->description,
            'tracks_count' => $playlist->nb_tracks,
            'image' => $playlist->picture_medium,
            'tracks' => $playlist->tracks->data,
        ];
    }

    public function importPlaylist(Playlist $playlist, $provider_playlist_id): int
    {
        $url = 'https://api.deezer.com/playlist/'.$provider_playlist_id.'/tracks';
        $importedTracks = [];
        $limit = 100;

        while ($url) {
            $response = Http::get($url.'?limit='.$limit);

            if (! $response->successful()) {
                break;
            }

            $tracks = $response->json();
            foreach ($tracks['data'] as $track) {
                $formatedTrack = $this->formatTrack($track);
                $importedTracks[] = ProcessImportTrack::dispatch($playlist, $formatedTrack)
                    ->onQueue('imports');
            }
            $url = $tracks['next'] ?? null;
        }

        return count(array_filter($importedTracks));
    }

    public function formatTrack(array $track): object
    {
        return (object) [
            'provider' => 'deezer',
            'provider_id' => $track['id'],
            'provider_url' => $track['link'],
            'provider_popularity' => $track['rank'],
            'artist_name' => $track['artist']['name'],
            'track_name' => $track['title'],
            'album_name' => $track['album']['title'],
            'preview_url' => $track['preview'],
            'release_date' => null, // $this->getReleaseDate($track['album']['id']), TOO SLOW!!
            'artwork_url' => $track['album']['cover_medium'],
        ];
    }
}
