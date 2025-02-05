<?php

namespace App\Services;

use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MusicProvidersService
{
    public function search(string $term)
    {
        $responses = Http::pool(fn (Pool $pool) => [
            $pool->get(route('providers.deezer.search.track', ['term' => $term])),
            $pool->get(route('providers.itunes.search.track', ['term' => $term])),
            // $pool->get(route('providers.spotify.search.track', ['term' => $term])),
            // $pool->get(route('providers.youtube.search.track', ['term' => $term])),
        ]);

        $merged = collect();

        foreach ($responses as $response) {
            if ($response->ok()) {
                $merged = $merged->merge($response->collect());
            } else {
                Log::error('Failed to fetch results from provider', [
                    'status' => $response->status(),
                    'error' => $response->body(),
                ]);
            }
        }

        $sorted = $merged->sortByDesc('provider_popularity')->sortByDesc(function ($item) use ($term) {
            $text1 = $item['artist_name'].' '.$item['track_name'];
            $text2 = $item['track_name'].' '.$item['artist_name'];
            $percent1 = similar_text($term, $text1, $percent1);
            $percent2 = similar_text($term, $text2, $percent2);

            return max($percent1, $percent2);
        });

        return $sorted;
    }
}
