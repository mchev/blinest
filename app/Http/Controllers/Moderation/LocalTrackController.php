<?php

namespace App\Http\Controllers\Moderation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Moderation\UpdateLocalTrackRequest;
use App\Models\LocalTrack;
use App\Services\Moderation\ModerationLocalTrackService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LocalTrackController extends Controller
{
    public function __construct(
        private ModerationLocalTrackService $localTracks,
    ) {}

    public function index(Request $request)
    {
        return Inertia::render('Moderation/LocalTracks', [
            'tracks' => $this->localTracks->paginateTracks(
                $request->string('search')->toString() ?: null,
                $request->string('sort_by', 'created_at')->toString(),
                $request->string('sort_direction', 'desc')->toString(),
                (int) $request->input('per_page', 20),
            ),
            'filters' => $request->only(['search', 'per_page', 'sort_by', 'sort_direction']),
        ]);
    }

    public function update(UpdateLocalTrackRequest $request, LocalTrack $localTrack)
    {
        $localTrack->update($request->validated());

        return back()->with('success', __('Moderation local track updated'));
    }

    public function destroy(Request $request, LocalTrack $localTrack)
    {
        try {
            $this->localTracks->deleteTrack($localTrack, $request->user());

            return back()->with('success', __('Moderation local track deleted'));
        } catch (\Exception $e) {
            report($e);

            return back()->withErrors([
                'error' => __('Moderation local track delete failed'),
            ]);
        }
    }
}
