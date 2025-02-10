<?php

namespace App\Services\MusicProviders;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class JamendoService
{
    protected string $apiKey;

    protected string $baseUrl = 'https://api.jamendo.com/v3.0';

    public function __construct()
    {
        $this->apiKey = config('services.jamendo.client_id');
    }

    public function searchTrack()
    {
        try {
            $term = Request::get('term');
            if (empty($term)) {
                return collect([]);
            }

            $query = urlencode(trim($term));
            $response = Http::get($this->baseUrl.'/tracks/', [
                'client_id' => $this->apiKey,
                'format' => 'json',
                'limit' => 10,
                'search' => $query,
                'audioformat' => 'mp32',
            ]);

            if (! $response->successful()) {
                return collect([[
                    'error' => true,
                    'provider' => 'jamendo',
                    'message' => 'Jamendo : '.$response->body(),
                    'status_code' => $response->status(),
                ]]);
            }

            $collection = $response->collect();
            $results = $collection['results'] ?? null;

            if (empty($results)) {
                return collect([[
                    'error' => true,
                    'provider' => 'jamendo',
                    'message' => 'Jamendo : Aucune piste trouvée',
                    'status_code' => 404,
                ]]);
            }

            $filteredTracks = collect($results)->filter(function ($track) {
                return ! empty($track['audio']) &&
                       filter_var($track['audio'], FILTER_VALIDATE_URL);
            });

            if (count($results) > 0 && $filteredTracks->isEmpty()) {
                return collect([[
                    'error' => true,
                    'provider' => 'jamendo',
                    'message' => 'Jamendo : Les pistes trouvées ne sont pas disponibles en streaming',
                    'status_code' => 404,
                ]]);
            }

            return $filteredTracks->map(fn ($track) => $this->formatTrack($track));

        } catch (\Exception $e) {
            Log::error('Jamendo search failed', [
                'term' => $term ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return collect([[
                'error' => true,
                'provider' => 'jamendo',
                'message' => 'Jamendo : '.$e->getMessage(),
                'status_code' => 500,
            ]]);
        }
    }

    protected function formatTrack(array $track): object
    {
        return (object) [
            'provider' => 'jamendo',
            'provider_id' => $track['id'],
            'provider_url' => $track['shareurl'] ?? "https://www.jamendo.com/track/{$track['id']}",
            'provider_popularity' => 0,
            'artist_name' => $track['artist_name'],
            'track_name' => $track['name'],
            'album_name' => $track['album_name'] ?? null,
            'preview_url' => $track['audio'] ?? null,
            'release_date' => Carbon::parse($track['releasedate'])->format('Y-m-d'),
            'artwork_url' => $track['image'] ?? null,
        ];
    }
}
