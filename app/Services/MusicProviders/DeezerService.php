<?php

namespace App\Services\MusicProviders;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;

class DeezerService
{
    public function searchTrack()
    {
        $term = Request::get('term');

        $query = filter_var($term, FILTER_SANITIZE_STRING);
        $query = trim($query);
        $query = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $query);
        $query = str_replace(' ', '+', $query);

        $url = 'https://api.deezer.com/search/track?q='.$query;

        $collection = Http::get($url)->collect();

        $results = (isset($collection['data'])) ? collect($collection['data']) : null;

        $tracks = ($results) ? $results->where('readable')->map(function ($track) {
            return [
                'provider' => 'deezer',
                'provider_id' => $track['id'],
                'provider_url' => $track['link'],
                'artist_name' => $track['artist']['name'],
                'track_name' => $track['title'],
                'album_name' => $track['album']['title'],
                'preview_url' => $track['preview'],
                'release_date' => null, //$this->getReleaseDate($track['album']['id']), TOO SLOW!!
                'artwork_url' => $track['album']['cover_medium'],
            ];
        }) : null;

        return $tracks;
    }

    public function getReleaseDate($album)
    {
        $url = 'https://api.deezer.com/album/'.$album;
        $collection = Http::get($url)->collect();

        return Carbon::parse($collection['release_date'])->format('Y-m-d');
    }
}
