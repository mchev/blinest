<?php

namespace App\Services\MusicProviders;

use App\Jobs\ProcessImportTrack;
use App\Models\Playlist;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class YouTubeMusicService
{
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.youtube.api_key');
    }

    public function searchTrack()
    {
        try {
            $term = Request::get('term');
            if (empty($term)) {
                return collect([]);
            }

            // Check if quota is exceeded first
            if ($this->isQuotaExceeded()) {
                return collect([[
                    'error' => true,
                    'provider' => 'youtube',
                    'message' => __('La recherche YouTube est temporairement indisponible - Le quota quotidien est dépassé'),
                    'quota_exceeded' => true,
                    'reset_time' => $this->getQuotaResetTime(),
                    'status_code' => 429,
                ]]);
            }

            // Check cache first
            $cacheKey = 'youtube_search_'.md5($term);
            if ($cachedResults = Cache::get($cacheKey)) {
                return collect($cachedResults);
            }

            $query = urlencode(trim($term));
            $response = Http::withHeaders([
                'Referer' => config('app.url'),
            ])->get('https://www.googleapis.com/youtube/v3/search', [
                'key' => $this->apiKey,
                'part' => 'snippet',
                'q' => $query.' music',
                'type' => 'video',
                'videoCategoryId' => '10',
                'maxResults' => 5,
                'videoEmbeddable' => true,
                // Optimize quota by requesting only needed fields
                'fields' => 'items(id/videoId,snippet(title,channelTitle,publishedAt,thumbnails/default))',
            ]);

            // Enhanced error handling
            if (! $response->successful()) {
                return collect([$this->handleApiError($response)]);
            }

            $data = $response->json();
            $results = collect($data['items'] ?? [])->map(fn ($track) => $this->formatTrack($track));

            // Cache successful results
            Cache::put($cacheKey, $results->toArray(), now()->addHours(1));

            return $results;

        } catch (\Exception $e) {
            Log::error('YouTube Music search failed', [
                'term' => $term ?? null,
                'error' => $e->getMessage(),
            ]);

            return collect([[
                'error' => true,
                'provider' => 'youtube',
                'message' => __('YouTube service is temporarily unavailable'),
                'status_code' => 500,
            ]]);
        }
    }

    protected function formatTrack(array $track): object
    {
        // Extract artist and track name from video title
        $title = $track['snippet']['title'];
        $parts = explode(' - ', $title, 2);
        $artistName = count($parts) > 1 ? trim($parts[0]) : $track['snippet']['channelTitle'];
        $trackName = count($parts) > 1 ? trim($parts[1]) : $title;

        $videoId = $track['id']['videoId'] ?? $track['snippet']['resourceId']['videoId'];

        return (object) [
            'provider' => 'youtube',
            'provider_id' => $videoId,
            'provider_url' => 'https://www.youtube.com/watch?v='.$videoId,
            'provider_popularity' => 0, // YouTube API doesn't provide view count in basic search
            'artist_name' => $artistName,
            'track_name' => $trackName,
            'album_name' => null,
            'preview_url' => $videoId,
            'release_date' => Carbon::parse($track['snippet']['publishedAt'])->format('Y-m-d'),
            'artwork_url' => $track['snippet']['thumbnails']['default']['url'] ?? null,
        ];
    }

    protected function handleApiError($response): array
    {
        $body = $response->body();
        $status = $response->status();

        if ($status === 403 || $status === 429) {
            if (str_contains(strtolower($body), 'quota') || $status === 429) {
                $this->markQuotaExceeded();

                return [
                    'error' => true,
                    'provider' => 'youtube',
                    'message' => __('La recherche YouTube est temporairement indisponible - Le quota quotidien est dépassé'),
                    'quota_exceeded' => true,
                    'reset_time' => $this->getQuotaResetTime(),
                    'status_code' => $status,
                ];
            }

            return [
                'error' => true,
                'provider' => 'youtube',
                'message' => 'API access denied',
                'status_code' => $status,
            ];
        }

        return [
            'error' => true,
            'provider' => 'youtube',
            'message' => 'Service unavailable',
            'status_code' => $status,
        ];
    }

    protected function getQuotaResetTime(): string
    {
        return now()
            ->setTimezone('America/Los_Angeles')
            ->addDay()
            ->startOfDay()
            ->toISOString();
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

    protected function handleQuotaExceeded(): array
    {
        $this->markQuotaExceeded();
        $resetTime = now()
            ->setTimezone('America/Los_Angeles')
            ->addDay()
            ->startOfDay();

        Log::warning('YouTube API quota exceeded', [
            'reset_time' => $resetTime,
        ]);

        return [
            'error' => true,
            'message' => __('La recherche YouTube est temporairement indisponible - Le quota quotidien est dépassé'),
            'provider' => 'youtube',
            'reset_time' => $resetTime->toISOString(),
            'quota_exceeded' => true,
        ];
    }

    protected function isQuotaExceeded(): bool
    {
        if (Cache::has('youtube_api_quota_exceeded')) {
            $resetTime = Cache::get('youtube_api_quota_reset_time');
            if ($resetTime && now()->lessThan($resetTime)) {
                return true;
            }
            // If we're past reset time, clear the cache
            Cache::forget('youtube_api_quota_exceeded');
            Cache::forget('youtube_api_quota_reset_time');
        }

        return false;
    }

    protected function markQuotaExceeded(): void
    {
        // Cache the quota exceeded status until midnight PST (when YouTube resets quotas)
        $resetTime = now()
            ->setTimezone('America/Los_Angeles')
            ->addDay()
            ->startOfDay();

        Cache::put('youtube_api_quota_exceeded', true, $resetTime);
        Cache::put('youtube_api_quota_reset_time', $resetTime, $resetTime);
    }
}
