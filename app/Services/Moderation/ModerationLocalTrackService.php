<?php

namespace App\Services\Moderation;

use App\Jobs\ProcessDeletedTrack;
use App\Models\LocalTrack;
use App\Models\Track;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class ModerationLocalTrackService
{
    /**
     * @return LengthAwarePaginator<int, array<string, mixed>>
     */
    public function paginateTracks(
        ?string $search,
        string $sortBy = 'created_at',
        string $sortDirection = 'desc',
        int $perPage = 20,
    ): LengthAwarePaginator {
        $allowedSorts = ['created_at', 'track_name', 'artist_name', 'playlists_usage_count'];

        if (! in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'created_at';
        }

        $sortDirection = $sortDirection === 'asc' ? 'asc' : 'desc';

        return LocalTrack::query()
            ->when(filled($search), fn (Builder $query) => $this->applySearch($query, $search))
            ->with(['user:id,name,photo_path'])
            ->select('local_tracks.*')
            ->selectSub(
                Track::query()
                    ->selectRaw('count(*)')
                    ->where('tracks.provider', 'local')
                    ->whereColumn('tracks.provider_id', 'local_tracks.id'),
                'playlists_usage_count'
            )
            ->orderBy($sortBy, $sortDirection)
            ->paginate($perPage)
            ->withQueryString()
            ->through(fn (LocalTrack $track) => $this->formatTrack($track));
    }

    /**
     * @return array<string, mixed>
     */
    public function formatTrack(LocalTrack $track): array
    {
        return [
            'id' => $track->id,
            'track_name' => $track->track_name,
            'artist_name' => $track->artist_name,
            'audio_url' => $track->audio_url,
            'artwork_url' => $track->artwork_url,
            'created_at' => $track->created_at?->toIso8601String(),
            'playlists_usage_count' => (int) ($track->playlists_usage_count ?? 0),
            'uploader' => $track->user ? [
                'id' => $track->user->id,
                'name' => $track->user->name,
                'photo' => $track->user->photo,
                'profile_url' => route('moderation.users.show', $track->user),
            ] : null,
        ];
    }

    public function deleteTrack(LocalTrack $localTrack, User $actor): void
    {
        if ($localTrack->audio_path) {
            Storage::delete($localTrack->audio_path);
        }

        if ($localTrack->artwork_path) {
            Storage::delete($localTrack->artwork_path);
        }

        $tracks = Track::query()
            ->where('provider', 'local')
            ->where('provider_id', (string) $localTrack->id)
            ->get();

        foreach ($tracks as $track) {
            ProcessDeletedTrack::dispatch($track, $actor);
        }

        $localTrack->delete();
    }

    private function applySearch(Builder $query, string $search): void
    {
        $term = trim($search);

        $query->where(function (Builder $query) use ($term) {
            $query->where('track_name', 'like', '%'.$term.'%')
                ->orWhere('artist_name', 'like', '%'.$term.'%');

            if (ctype_digit($term)) {
                $query->orWhere('id', (int) $term)
                    ->orWhere('user_id', (int) $term);
            }
        });
    }
}
