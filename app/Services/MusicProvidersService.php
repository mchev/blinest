<?php

namespace App\Services;

use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;

class MusicProvidersService
{
    public function search(string $term)
    {
        $responses = Http::pool(fn (Pool $pool) => [
            $pool->get(route('providers.deezer.search.track', ['term' => $term])),
            $pool->get(route('providers.itunes.search.track', ['term' => $term])),
            $pool->get(route('providers.spotify.search.track', ['term' => $term])),
        ]);

        $merged = collect();

        foreach ($responses as $response) {
            if ($response->ok()) {
                $merged = $merged->merge($response->collect());
            }
        }

        return $merged;
    }
}
