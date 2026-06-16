<?php

namespace App\Events;

use App\Models\Round;
use App\Models\Track;
use App\Services\TrackTimingService;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TrackEnded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The round instance.
     *
     * @var Round
     */
    public $round;

    /**
     * The track that ended.
     *
     * @var Track|null
     */
    public $track;

    /**
     * Create a new event instance.
     */
    public function __construct(Round $round)
    {
        $this->round = $round;
        $this->track = $this->getCurrentTrack();
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): PresenceChannel
    {
        return new PresenceChannel('rooms.'.$this->round->room_id);
    }

    /**
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        $this->round->loadMissing('room');
        $timing = app(TrackTimingService::class);

        return [
            'round' => [
                'id' => $this->round->id,
                'current' => $this->round->current,
            ],
            'track' => $this->track?->toPlaylistPayload(),
            'next_track' => $this->nextTrackPreview(),
            ...$timing->interTrackPausePayload($this->round, now()),
        ];
    }

    /**
     * @return array{id: int, provider: string, audio: string|null, preview_url: string|null}|null
     */
    private function nextTrackPreview(): ?array
    {
        $tracks = $this->round->tracks ?? [];

        if ($this->round->current >= count($tracks)) {
            return null;
        }

        $trackId = $tracks[$this->round->current] ?? null;

        if ($trackId === null) {
            return null;
        }

        $track = Track::query()->find($trackId);

        if ($track === null) {
            return null;
        }

        return [
            'id' => $track->id,
            'provider' => $track->provider,
            'audio' => $track->audio,
            'preview_url' => $track->preview_url,
        ];
    }

    /**
     * Get the current track that just ended.
     */
    private function getCurrentTrack(): ?Track
    {
        $current = ($this->round->current ?? 1) - 1;

        if ($current < 0 || ! isset($this->round->tracks[$current])) {
            return null;
        }

        try {
            return Track::with(['answers.type'])->findOrFail($this->round->tracks[$current]);
        } catch (ModelNotFoundException $e) {
            Log::error('Track not found', ['track_id' => $this->round->tracks[$current]]);

            return null;
        }
    }
}
