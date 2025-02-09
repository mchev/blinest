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
            if (empty($term) || empty($providers)) {
                return [
                    'results' => collect(),
                    'errors' => collect(),
                ];
            }

            // Generate a cache key based on search parameters
            $cacheKey = "music_search_{$providers}_{$term}";

            // Try to get results from cache first
            return Cache::remember($cacheKey, now()->addHours(24), function () use ($term, $providers) {
                $providers = explode(',', $providers);
                $merged = collect();
                $errors = collect();

                $responses = Http::pool(fn (Pool $pool) => array_filter([
                    in_array('itunes', $providers) ?
                        $pool->get(route('providers.itunes.search.track', ['term' => $term])) : null,
                    in_array('audius', $providers) ?
                        $pool->get(route('providers.audius.search.track', ['term' => $term])) : null,
                    in_array('youtube', $providers) ?
                        $pool->get(route('providers.youtube.search.track', ['term' => $term])) : null,
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

                        // Only merge valid results
                        $validResults = $results->filter(function ($item) {
                            return is_array($item) &&
                                   ! isset($item['error']) &&
                                   isset($item['provider']) &&
                                   isset($item['provider_id']) &&
                                   isset($item['preview_url']);
                        });

                        $merged = $merged->merge($validResults);
                    } else {
                        Log::error('Provider request failed', [
                            'url' => $response->effectiveUri(),
                            'status' => $response->status(),
                            'body' => $response->body(),
                        ]);
                    }
                }

                return [
                    'results' => $merged,
                    'errors' => $errors,
                ];
            });
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
