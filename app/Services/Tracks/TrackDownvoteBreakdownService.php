<?php

namespace App\Services\Tracks;

use App\Models\Track;
use App\Models\Vote;
use Illuminate\Support\Collection;

class TrackDownvoteBreakdownService
{
    /**
     * @param  Collection<int, int>|list<int>  $trackIds
     * @return array<int, array<string, int>>
     */
    public function forTracks(Collection|array $trackIds): array
    {
        $trackIds = collect($trackIds)->filter()->values();

        if ($trackIds->isEmpty()) {
            return [];
        }

        $morphClass = (new Track)->getMorphClass();

        $rows = Vote::query()
            ->where('votable_type', $morphClass)
            ->whereIn('votable_id', $trackIds)
            ->where('votes', '<', 0)
            ->whereNotNull('downvote_reason')
            ->selectRaw('votable_id, downvote_reason, COUNT(*) as total')
            ->groupBy('votable_id', 'downvote_reason')
            ->get();

        $breakdown = [];

        foreach ($rows as $row) {
            $reason = $row->downvote_reason instanceof \BackedEnum
                ? $row->downvote_reason->value
                : (string) $row->downvote_reason;

            $breakdown[(int) $row->votable_id][$reason] = (int) $row->total;
        }

        return $breakdown;
    }
}
