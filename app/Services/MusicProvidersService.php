<?php

namespace App\Services;

use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MusicProvidersService
{
    public function search(string $term, ?string $providers = null)
    {
        try {
            // Generate a cache key based on search parameters
            $cacheKey = "music_search_{$providers}_{$term}";

            // Try to get results from cache first
            return Cache::remember($cacheKey, now()->addHours(24), function () use ($term, $providers) {
                $providers = explode(',', $providers);

                $responses = Http::pool(fn (Pool $pool) => array_filter([
                    in_array('itunes', $providers) ?
                        $pool->get(route('providers.itunes.search.track', ['term' => $term])) : null,
                    in_array('audius', $providers) ?
                        $pool->get(route('providers.audius.search.track', ['term' => $term])) : null,
                    in_array('youtube', $providers) ?
                        $pool->get(route('providers.youtube.search.track', ['term' => $term])) : null,
                ]));

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
            });
        } catch (\Exception $e) {
            Log::error('Error in music provider search', [
                'error' => $e->getMessage(),
                'term' => $term,
            ]);

            return collect();
        }
    }
}
