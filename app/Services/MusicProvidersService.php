<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MusicProvidersService
{
    private const CACHE_TTL = 300; // 5 minutes

    private const REQUEST_TIMEOUT = 5; // 5 seconds

    public function search(User $user, string $term, ?string $providers = null)
    {
        try {
            if (empty($term) || empty($providers)) {
                return [
                    'results' => collect(),
                    'errors' => collect(),
                ];
            }

            $providers = explode(',', $providers);
            $cacheKey = "music_search:{$term}:".implode(',', $providers);

            // Try to get cached results first
            if ($cached = Cache::get($cacheKey)) {
                return [
                    'results' => collect($cached['results']),
                    'errors' => collect($cached['errors']),
                ];
            }

            $merged = collect();
            $errors = collect();

            $responses = Http::timeout(self::REQUEST_TIMEOUT)->pool(fn (Pool $pool) => array_filter([
                in_array('itunes', $providers) ?
                    $pool->get(route('providers.itunes.search.track', ['term' => $term])) : null,
                in_array('audius', $providers) ?
                    $pool->get(route('providers.audius.search.track', ['term' => $term])) : null,
                in_array('youtube', $providers) ?
                    $user->isPublicModerator() ?
                        $pool->get(route('providers.youtube.search.track', ['term' => $term])) :
                        $pool->get(route('providers.youtubeapi.search.track', ['term' => $term])) : null,
                in_array('spotify', $providers) ?
                    $pool->get(route('providers.spotify.search.track', ['term' => $term])) : null,
                in_array('deezer', $providers) ?
                    $pool->get(route('providers.deezer.search.track', ['term' => $term])) : null,
                in_array('local', $providers) ?
                    $pool->get(route('providers.local.search.track', ['term' => $term])) : null,
            ]));

            foreach ($responses as $response) {
                if ($response->ok()) {
                    $results = $response->collect();

                    // Check if the response is an error response
                    if ($results->first() && isset($results->first()['error'])) {
                        $error = $results->first();
                        Log::warning('Provider error encountered', $error);
                        $errors->push($error);

                        continue;
                    }

                    // Only merge valid results using a more efficient filter
                    $validResults = $results->filter(fn ($item) => is_array($item) &&
                        ! isset($item['error']) &&
                        isset($item['provider'], $item['provider_id'], $item['preview_url'])
                    );

                    $merged = $merged->merge($validResults);
                } else {
                    Log::error('Provider request failed', [
                        'url' => $response->effectiveUri(),
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);
                }
            }

            $result = [
                'results' => $merged->values()->all(),
                'errors' => $errors->values()->all(),
            ];

            // Cache the results
            Cache::put($cacheKey, $result, self::CACHE_TTL);

            return $result;
        } catch (\Exception $e) {
            Log::error('Error in music provider search: '.$e->getMessage(), [
                'term' => $term,
                'providers' => $providers,
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'results' => collect(),
                'errors' => collect([
                    [
                        'error' => true,
                        'message' => 'An unexpected error occurred',
                        'status_code' => 500,
                    ],
                ]),
            ];
        }
    }
}
