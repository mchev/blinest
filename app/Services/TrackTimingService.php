<?php

namespace App\Services;

use App\Models\Round;
use Carbon\CarbonInterface;

class TrackTimingService
{
    public const ANSWER_GRACE_SECONDS = 0.3;

    public function trackDuration(Round $round): int
    {
        $round->loadMissing('room');

        return (int) ($round->room?->track_duration ?? 30);
    }

    public function answerDeadlineAt(Round $round): ?CarbonInterface
    {
        if ($round->current_track_started_at === null) {
            return null;
        }

        return $round->current_track_started_at->copy()->addSeconds($this->trackDuration($round));
    }

    public function isAnswerWindowOpen(Round $round, ?CarbonInterface $now = null): bool
    {
        $deadline = $this->answerDeadlineAt($round);

        if ($deadline === null) {
            return true;
        }

        $now = $now ?? now();

        return $now->lte($deadline->copy()->addSeconds(self::ANSWER_GRACE_SECONDS));
    }

    public function elapsedSeconds(Round $round, ?CarbonInterface $now = null): ?float
    {
        if ($round->current_track_started_at === null) {
            return null;
        }

        return (float) $round->current_track_started_at->diffInSeconds($now ?? now(), true);
    }

    public function hasSpeedBonus(Round $round, ?CarbonInterface $now = null): bool
    {
        $elapsed = $this->elapsedSeconds($round, $now);

        if ($elapsed === null) {
            return false;
        }

        return $elapsed < ($this->trackDuration($round) * 0.18);
    }

    public function pauseBetweenTracks(Round $round): int
    {
        $round->loadMissing('room');

        return (int) ($round->room?->pause_between_tracks ?? 0);
    }

    public function nextTrackAt(Round $round, ?CarbonInterface $trackEndedAt = null): ?CarbonInterface
    {
        $endedAt = $trackEndedAt ?? $this->answerDeadlineAt($round);

        if ($endedAt === null) {
            return null;
        }

        return $endedAt->copy()->addSeconds($this->pauseBetweenTracks($round));
    }

    public function isInterTrackPause(Round $round, ?CarbonInterface $now = null): bool
    {
        $deadline = $this->answerDeadlineAt($round);
        $nextTrackAt = $this->nextTrackAt($round);

        if ($deadline === null || $nextTrackAt === null) {
            return false;
        }

        $now = $now ?? now();

        return $now->gt($deadline) && $now->lt($nextTrackAt);
    }

    /**
     * @return array{
     *     answer_deadline_at: string|null,
     *     next_track_at: string,
     *     pause_between_tracks: int
     * }
     */
    public function interTrackPausePayload(Round $round, ?CarbonInterface $trackEndedAt = null): array
    {
        $endedAt = $trackEndedAt ?? $this->answerDeadlineAt($round) ?? now();
        $pause = $this->pauseBetweenTracks($round);

        return [
            'answer_deadline_at' => $this->answerDeadlineAt($round)?->toIso8601String(),
            'next_track_at' => $endedAt->copy()->addSeconds($pause)->toIso8601String(),
            'pause_between_tracks' => $pause,
        ];
    }

    /**
     * @return array{
     *     current_track_started_at: string|null,
     *     answer_deadline_at: string|null,
     *     track_duration: int,
     *     track_sequence: int|null
     * }
     */
    public function timingPayload(Round $round): array
    {
        return [
            'current_track_started_at' => $round->current_track_started_at?->toIso8601String(),
            'answer_deadline_at' => $this->answerDeadlineAt($round)?->toIso8601String(),
            'track_duration' => $this->trackDuration($round),
            'track_sequence' => $round->current,
        ];
    }
}
