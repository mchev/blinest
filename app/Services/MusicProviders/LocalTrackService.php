<?php

namespace App\Services\MusicProviders;

use App\Models\LocalTrack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LocalTrackService
{
    public function searchTrack(Request $request)
    {
        $term = $request->get('term');

        if (empty($term)) {
            return collect([]);
        }

        $tracks = LocalTrack::search($term)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $formattedTracks = $tracks->map(function ($track) {
            return [
                'provider' => 'local',
                'provider_id' => $track->id,
                'provider_url' => route('local.track.audio', ['track' => $track->id]),
                'provider_popularity' => 0,
                'artist_name' => $track->artist_name,
                'track_name' => $track->track_name,
                'album_name' => null,
                'preview_url' => route('local.track.audio', ['track' => $track->id]),
                'artwork_url' => Storage::disk('ovh')->url($track->artwork_path),
            ];
        });

        return $formattedTracks;
    }
}
