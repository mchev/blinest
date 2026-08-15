<?php

namespace App\Services\MusicProviders;

use App\Jobs\ProcessImportTrack;
use App\Models\Playlist;
use App\Services\MusicProviders\Concerns\CachesResetTime;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class YoutubeWithoutApiService
{
    use CachesResetTime;

    protected string $baseUrl = 'https://www.youtube.com';

    protected array $userAgents = [
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0',
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Edge/120.0.0.0 Safari/537.36',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.2 Safari/605.1.15',
        'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:121.0) Gecko/20100101 Firefox/121.0',
        'Mozilla/5.0 (iPhone; CPU iPhone OS 17_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/120.0.6099.119 Mobile/15E148 Safari/604.1',
        'Mozilla/5.0 (iPad; CPU OS 17_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.2 Mobile/15E148 Safari/604.1',
        'Mozilla/5.0 (Linux; Android 14; SM-S918B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.6099.144 Mobile Safari/537.36',
    ];

    public function searchTrack()
    {
        try {
            $term = Request::get('term');
            if (empty($term)) {
                return collect([]);
            }

            // Check if rate limited
            if ($this->isRateLimited()) {
                return collect([[
                    'error' => true,
                    'provider' => 'youtube',
                    'message' => __('YouTube search is temporarily unavailable - Rate limit reached'),
                    'rate_limited' => true,
                    'reset_time' => $this->getRateLimitResetTime(),
                    'status_code' => 429,
                ]]);
            }

            // Check cache first
            $cacheKey = 'youtube_search_'.md5($term);
            if ($cachedResults = Cache::get($cacheKey)) {
                return collect($cachedResults);
            }

            $query = str_replace(' ', '+', trim($term));
            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => $this->getRandomUserAgent(),
                    'Accept-Language' => 'en-US,en;q=0.9',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                    'Referer' => $this->baseUrl,
                    'DNT' => '1',
                ])->get("{$this->baseUrl}/results", [
                    'search_query' => $query,
                ]);

            if (! $response->successful()) {
                $this->handleFailedRequest($response);

                return collect([$this->handleApiError($response)]);
            }

            $html = $response->body();
            $results = $this->parseSearchResults($html);

            if ($results->isEmpty()) {
                $this->incrementFailedAttempts();

                return collect([[
                    'error' => true,
                    'provider' => 'youtube',
                    'message' => __('No results found'),
                    'status_code' => 404,
                ]]);
            }

            // Cache successful results
            Cache::put($cacheKey, $results->toArray(), now()->addHours(1));
            $this->resetFailedAttempts();

            return $results;

        } catch (\Exception $e) {
            $this->incrementFailedAttempts();
            Log::error('YouTube Music search failed', [
                'term' => $term ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return collect([[
                'error' => true,
                'provider' => 'youtube',
                'message' => __('YouTube service is temporarily unavailable'),
                'status_code' => 500,
            ]]);
        }
    }

    protected function handleApiError($response): array
    {
        $status = $response->status();
        $body = $response->body();

        if ($status === 403 || $status === 429 || str_contains($body, 'denied')) {
            $this->markRateLimited();

            return [
                'error' => true,
                'provider' => 'youtube',
                'message' => __('YouTube search is temporarily unavailable - Rate limit reached'),
                'rate_limited' => true,
                'reset_time' => $this->getRateLimitResetTime(),
                'status_code' => $status,
            ];
        }

        return [
            'error' => true,
            'provider' => 'youtube',
            'message' => __('YouTube service is temporarily unavailable'),
            'status_code' => $status,
        ];
    }

    protected function getRandomUserAgent(): string
    {
        return $this->userAgents[array_rand($this->userAgents)];
    }

    protected function isRateLimited(): bool
    {
        return $this->isBeforeCachedResetTime(
            'youtube_scraping_rate_limited',
            'youtube_scraping_reset_time',
        );
    }

    protected function markRateLimited(): void
    {
        $resetTime = now()->addHours(1);

        $this->cacheResetTime(
            'youtube_scraping_rate_limited',
            'youtube_scraping_reset_time',
            $resetTime,
        );
    }

    protected function getRateLimitResetTime(): string
    {
        return now()
            ->setTimezone('America/Los_Angeles')
            ->addHours(1)
            ->toISOString();
    }

    protected function incrementFailedAttempts(): void
    {
        $key = 'youtube_scraping_failed_attempts';
        $attempts = Cache::get($key, 0) + 1;
        Cache::put($key, $attempts, now()->addHours(1));

        if ($attempts >= 5) {
            $this->markRateLimited();
        }
    }

    protected function resetFailedAttempts(): void
    {
        Cache::forget('youtube_scraping_failed_attempts');
    }

    protected function handleFailedRequest($response): void
    {
        $this->incrementFailedAttempts();
        Log::warning('YouTube scraping request failed', [
            'status' => $response->status(),
            'body' => $response->body(),
            'headers' => $response->headers(),
        ]);
    }

    protected function formatTrack(array $track): object
    {
        // Extract artist and track name from video title
        $title = $track['snippet']['title'];
        $parts = explode(' - ', $title, 2);
        $artistName = count($parts) > 1 ? trim($parts[0]) : $track['snippet']['channelTitle'];
        $trackName = count($parts) > 1 ? trim($parts[1]) : $title;

        $videoId = $track['id']['videoId'];

        return (object) [
            'provider' => 'youtube',
            'provider_id' => $videoId,
            'provider_url' => 'https://www.youtube.com/watch?v='.$videoId,
            'provider_popularity' => 0,
            'artist_name' => $artistName,
            'track_name' => $trackName,
            'album_name' => null,
            'preview_url' => $videoId,
            'release_date' => Carbon::parse($track['snippet']['publishedAt'])->format('Y-m-d'),
            'artwork_url' => $track['snippet']['thumbnails']['default']['url'] ?? null,
        ];
    }

    protected function processImportedTracks(Playlist $playlist, array $tracks): int
    {
        return collect($tracks)->map(function ($item, $index) use ($playlist) {
            $formattedTrack = $this->formatTrack($item);

            return ProcessImportTrack::dispatch($playlist, $formattedTrack)
                ->onQueue('imports')
                ->delay(now()->addSeconds($index * 0.5));
        })->filter()->count();
    }

    protected function parseSearchResults(string $html): Collection
    {
        try {
            // Extract initial data from YouTube's response
            if (! preg_match('/var ytInitialData = ({.+?});/', $html, $matches)) {
                return collect([]);
            }

            $data = json_decode($matches[1], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Failed to decode YouTube JSON data', [
                    'error' => json_last_error_msg(),
                ]);

                return collect([]);
            }

            $items = collect($data['contents']['twoColumnSearchResultsRenderer']['primaryContents']['sectionListRenderer']['contents'] ?? [])
                ->flatMap(function ($content) {
                    return $content['itemSectionRenderer']['contents'] ?? [];
                })
                ->filter(function ($item) {
                    return isset($item['videoRenderer']) &&
                           isset($item['videoRenderer']['videoId']) &&
                           isset($item['videoRenderer']['title']['runs'][0]['text']);
                })
                ->take(5)
                ->map(function ($item) {
                    $video = $item['videoRenderer'];

                    return [
                        'id' => [
                            'videoId' => $video['videoId'],
                        ],
                        'snippet' => [
                            'title' => $video['title']['runs'][0]['text'] ?? '',
                            'channelTitle' => $video['ownerText']['runs'][0]['text'] ?? '',
                            'publishedAt' => $video['publishedTimeText']['simpleText'] ?? now()->toDateTimeString(),
                            'thumbnails' => [
                                'default' => [
                                    'url' => $video['thumbnail']['thumbnails'][0]['url'] ?? null,
                                ],
                            ],
                        ],
                    ];
                })
                ->map(fn ($track) => $this->formatTrack($track));

            return $items;
        } catch (\Exception $e) {
            Log::error('Failed to parse YouTube search results', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return collect([]);
        }
    }
}
