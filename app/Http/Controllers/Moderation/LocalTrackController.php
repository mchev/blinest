<?php

namespace App\Http\Controllers\Moderation;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessDeletedTrack;
use App\Models\LocalTrack;
use App\Models\Track;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class LocalTrackController extends Controller
{
    public function index(Request $request)
    {
        $query = LocalTrack::search($request->search)
            ->with('user');

        // Add sorting
        $sortBy = $request->sort_by ?? 'created_at';
        $sortDirection = $request->sort_direction ?? 'desc';

        $query->orderBy($sortBy, $sortDirection);

        $tracks = $query->paginate($request->per_page ?? 15)
            ->withQueryString()
            ->through(function ($track) {
                return [
                    'id' => $track->id,
                    'artist_name' => $track->artist_name,
                    'track_name' => $track->track_name,
                    'audio_url' => $track->audio_url,
                    'artwork_url' => $track->artwork_url,
                    'user' => $track->user,
                    'created_at' => $track->created_at->format('d/m/Y H:i'),
                ];
            });

        return Inertia::render('Moderation/LocalTracks', [
            'tracks' => $tracks,
            'filters' => $request->only(['search', 'per_page', 'sort_by', 'sort_direction']),
        ]);
    }

    public function update(Request $request, LocalTrack $localTrack)
    {
        $validated = $request->validate([
            'track_name' => 'required|string|max:255',
            'artist_name' => 'required|string|max:255',
        ]);

        $localTrack->update($validated);

        return back()->with('success', 'Track updated successfully.');
    }

    public function destroy(Request $request, LocalTrack $localTrack)
    {
        // Delete the files from S3 storage if they exist
        if ($localTrack->audio_path) {
            Storage::delete($localTrack->audio_path);
        }

        if ($localTrack->artwork_path) {
            Storage::delete($localTrack->artwork_path);
        }

        // Delete the playlist tracks associated with the local track
        $tracks = Track::where('provider', 'local')->where('provider_id', $localTrack->id)->get();

        // Dispatch the deletion of the tracks
        foreach ($tracks as $track) {
            ProcessDeletedTrack::dispatch($track, $request->user());
        }

        // Delete the local track
        $localTrack->delete();

        return back()->with('success', 'Track and associated files deleted successfully.');
    }
}
