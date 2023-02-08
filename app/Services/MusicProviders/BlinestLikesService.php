<?php

namespace App\Services\MusicProviders;

use App\Models\Playlist;
use App\Models\Track;
use Illuminate\Support\Facades\Auth;

class BlinestLikesService
{
    public function getLikesInformation(): object
    {
        return (object) [
            'id' => null,
            'name' => Auth::user()->name.' likes',
            'description' => 'Tous les titres que tu as liké sur Blinest',
            'tracks_count' => Auth::user()->getUpVotedItems(Track::class)->count(),
            'image' => Auth::user()->photo,
        ];
    }

    public function importPlaylist(Playlist $playlist): int
    {
        $tracks = Auth::user()->getUpVotedItems(Track::class)->get();
        $importedTracks = [];

        foreach ($tracks as $track) {
            if ($playlist->tracks()->where('provider', $track->provider)->where('provider_id', $track->provider_id)->doesntExist()) {
                $duplicatedTrack = $playlist->tracks()->create([
                    'provider' => $track->provider,
                    'provider_id' => $track->provider_id,
                    'provider_url' => $track->provider_url,
                    'preview_url' => $track->preview_url,
                    'artwork_url' => $track->artwork_url,
                ]);

                foreach ($track->answers as $answer) {
                    $duplicatedTrack->answers()->create([
                        'answer_type_id' => $answer->answer_type_id,
                        'value' => $answer->value,
                        'score' => $answer->score,
                    ]);
                }

                $importedTracks[] = $duplicatedTrack;
            }
        }

        return count(array_filter($importedTracks));
    }
}
