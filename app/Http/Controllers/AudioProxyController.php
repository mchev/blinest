<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AudioProxyController extends Controller
{
    const CACHE_TTL = 120; // 2 minutes

    public function __invoke(Request $request)
    {
        $url = $request->query('url');
        if (! $url) {
            return response()->json(['error' => 'No URL provided'], 400);
        }

        // Generate a cache key based on the URL
        $cacheKey = 'audio_preview_'.md5($url);

        try {
            $audioData = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($url) {
                $response = Http::timeout(5)->get($url);

                if (! $response->successful()) {
                    throw new \Exception('Provider returned status code: '.$response->status());
                }

                return $response->body();
            });

            return response($audioData, 200, [
                'Content-Type' => 'audio/mpeg',
                'Access-Control-Allow-Origin' => '*',
                'Cache-Control' => 'public, max-age='.self::CACHE_TTL,
            ]);

        } catch (\Exception $e) {
            Log::error('Audio proxy error', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Failed to fetch audio',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
