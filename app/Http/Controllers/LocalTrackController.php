<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocalTrackRequest;
use App\Models\LocalTrack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Image;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LocalTrackController extends Controller
{
    public function store(LocalTrackRequest $request)
    {
        $validated = $request->validated();

        try {
            // Upload audio file
            $audio_path = Storage::disk('ovh')->put('uploads', $validated['audio']);

            // Upload artwork file
            $artwork_path = $this->processAndStoreArtwork($validated);

            // Create track record
            $track = LocalTrack::create([
                'audio_path' => $audio_path,
                'artwork_path' => $artwork_path,
                'artist_name' => $validated['artist_name'],
                'track_name' => $validated['track_name'],
                'user_id' => $request->user()->id,
            ]);

            return Redirect::back()->withSuccess("L'audio a été importé. Vous pouvez maintenant l'ajouter à la playlist depuis la liste Blinest.");

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'importation: '.$e->getMessage());

            // Clean up any uploaded files if an error occurs
            if (isset($audio_path) && Storage::disk('ovh')->exists($audio_path)) {
                Storage::disk('ovh')->delete($audio_path);
            }

            if (isset($artwork_path) && Storage::disk('ovh')->exists($artwork_path)) {
                Storage::disk('ovh')->delete($artwork_path);
            }

            return Redirect::back()->withError('Erreur lors de l\'importation: '.$e->getMessage());
        }
    }

    /**
     * Process and store artwork image
     *
     * @param  array  $validated  Validated request data
     * @return string Path to stored artwork
     *
     * @throws \Exception If artwork processing fails
     */
    private function processAndStoreArtwork(array $validated): string
    {
        try {
            $filename = Str::slug($validated['artist_name']).'_'.Str::slug($validated['track_name']).'.webp';

            $storedPath = Image::fromUpload($validated['artwork'])
                ->contain(300, 300)
                ->toWebp()
                ->storePubliclyAs('artworks', $filename, 'ovh');

            if ($storedPath === false) {
                throw new \RuntimeException('Unable to store artwork on disk.');
            }

            return $storedPath;
        } catch (\Exception $e) {
            Log::error('Erreur lors du traitement de la pochette: '.$e->getMessage());
            throw new \Exception('Erreur lors du traitement de la pochette: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LocalTrack $localTrack)
    {
        try {
            // Delete the files from storage
            if (Storage::disk('ovh')->exists($localTrack->audio_path)) {
                Storage::disk('ovh')->delete($localTrack->audio_path);
            }
            if (Storage::disk('ovh')->exists($localTrack->artwork_path)) {
                Storage::disk('ovh')->delete($localTrack->artwork_path);
            }

            // Delete the database record
            $localTrack->delete();

            return Redirect::back()->withSuccess('La piste a été supprimée avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de la piste: '.$e->getMessage());

            return Redirect::back()->withError('Erreur lors de la suppression de la piste.');
        }
    }

    /**
     * Serve the audio file with proper CORS headers
     * Cached for 1 minute
     */
    public function audio(LocalTrack $track)
    {
        if (! Storage::disk('ovh')->exists($track->audio_path)) {
            abort(404);
        }

        $headers = [
            'Content-Type' => 'audio/mpeg',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, HEAD',
            'Access-Control-Allow-Headers' => '*',
            'Cache-Control' => 'public, max-age=60',
        ];

        return response()->stream(
            function () use ($track) {
                $stream = Storage::disk('ovh')->readStream($track->audio_path);
                fpassthru($stream);
                fclose($stream);
            },
            200,
            $headers
        );
    }
}
