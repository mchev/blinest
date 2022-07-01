<?php

namespace App\Services\MusicProviders;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;

class AppleMusicService
{
    public function searchTrack()
    {
        $term = Request::get('term');

        $query = filter_var($term, FILTER_SANITIZE_STRING);
        $query = trim($query);
        $query = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $query);
        $query = str_replace(' ', '+', $query);

        $url = 'https://itunes.apple.com/search?term='.$query.'&country='.config('app.locale').'&entity=musicTrack&limit=10&output=json';

        $collection = Http::get($url)->collect();

        $results = (isset($collection['results'])) ? collect($collection['results']) : null;

        $tracks = ($results) ? $results->where('isStreamable')->map(function ($track) {
            return [
                'provider' => 'itunes',
                'provider_id' => $track['trackId'],
                'provider_url' => $track['trackViewUrl'],
                'artist_name' => $track['artistName'],
                'track_name' => $track['trackName'],
                'album_name' => $track['collectionName'],
                'preview_url' => $track['previewUrl'],
                'release_date' => Carbon::parse($track['releaseDate'])->format('Y-m-d'),
                'artwork_url' => $track['artworkUrl100'],
            ];
        }) : null;

        return $tracks;
    }
}
