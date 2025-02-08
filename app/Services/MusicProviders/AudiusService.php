<?php

namespace App\Services\MusicProviders;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class AudiusService
{
    protected string $apiHost;

    public function __construct()
    {
        // Audius requires getting a host first
        $this->apiHost = $this->getHost();
    }

    protected function getHost(): string
    {
        try {
            $response = Http::get('https://api.audius.co');
            $data = $response->json();

            // Get all available hosts and randomly select one that's working
            $hosts = $data['data'] ?? [];
            if (empty($hosts)) {
                throw new \Exception('No Audius API hosts available');
            }

            // Shuffle hosts to randomize selection
            shuffle($hosts);

            // Test each host until we find one that responds
            foreach ($hosts as $host) {
                try {
                    $test = Http::timeout(2)->get($host.'/v1/health_check');
                    if ($test->successful()) {
                        return $host;
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }

            // If no working hosts found, use first one as fallback
            return $hosts[0];

        } catch (\Exception $e) {
            Log::error('Failed to get Audius API host', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function searchTrack()
    {
        try {
            $term = Request::get('term');
            if (empty($term)) {
                Log::debug('Audius: Empty search term');

                return null;
            }

            $query = urlencode(trim($term));
            $url = "{$this->apiHost}/v1/tracks/search";

            $response = Http::get($url, [
                'query' => $query,
                'limit' => 10,
                'app_name' => config('services.audius.app_name'),
                'search_type' => 'all',
            ]);

            if (! $response->successful()) {
                return null;
            }

            $data = $response->json();

            return isset($data['data'])
                ? collect($data['data'])
                    ->filter(fn ($track) => ! empty($track['id']))
                    ->map(fn ($track) => $this->formatTrack($track))
                : null;

        } catch (\Exception $e) {
            Log::error('Audius search failed', [
                'term' => $term,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    public function formatTrack(array $track): object
    {
        $host = $this->apiHost;
        $previewUrl = null;

        if ($host && ! empty($track['id'])) {
            $previewUrl = "{$host}/v1/tracks/{$track['id']}/stream";
        }

        return (object) [
            'provider' => 'audius',
            'provider_id' => $track['id'],
            'provider_url' => $track['permalink'] ? 'https://audius.co'.$track['permalink'] : "https://audius.co/tracks/{$track['id']}",
            'provider_popularity' => $track['play_count'] ?? 0,
            'artist_name' => $track['user']['name'],
            'track_name' => $track['title'],
            'album_name' => $track['playlist_name'] ?? null,
            'preview_url' => $previewUrl,
            'release_date' => ! empty($track['release_date']) ? Carbon::parse($track['release_date'])->format('Y-m-d') : null,
            'artwork_url' => $track['artwork']['480x480'] ?? null,
        ];
    }

    public function getLiveTrackPreview($providerId)
    {
        $url = "{$this->apiHost}/v1/tracks/{$providerId}/stream";

        return $url;
    }
}
