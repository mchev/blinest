<?php

namespace App\Http\Controllers\Moderation;

use App\Http\Controllers\Controller;
use App\Models\LocalTrack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class LocalTrackController extends Controller
{
    public function index(Request $request)
    {
        $query = LocalTrack::search($request->search)
            ->orderBy('created_at', 'desc');

        $tracks = $query->paginate($request->per_page ?? 15)->withQueryString();

        return Inertia::render('Moderation/LocalTracks', [
            'tracks' => $tracks,
            'filters' => $request->only(['search', 'per_page']),
        ]);
    }

    public function destroy(LocalTrack $localTrack)
    {
        // Delete the files from S3 storage if they exist
        if ($localTrack->audio_path) {
            Storage::delete($localTrack->audio_path);
        }

        if ($localTrack->artwork_path) {
            Storage::delete($localTrack->artwork_path);
        }

        // Delete the database record
        $localTrack->delete();

        return back()->with('success', 'Track and associated files deleted successfully.');
    }
}
