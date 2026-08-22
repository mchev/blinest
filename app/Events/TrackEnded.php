<?php

namespace App\Events;

use App\Models\Round;
use App\Models\Track;
use App\Services\Tracks\TrackAnswerCacheService;
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
        $room = $this->round->room;
        $tracks = array_values((array) ($this->round->tracks ?? []));
        $isLastTrack = $this->round->current >= count($tracks);
        $nextTrackId = ! $isLastTrack && isset($tracks[$this->round->current])
            ? (int) $tracks[$this->round->current]
            : null;
        $nextTrackPreviewUrl = null;
        $nextTrackAudioUrl = null;

        if ($nextTrackId !== null) {
            $nextTrack = Track::query()->find($nextTrackId);
            $nextTrackPreviewUrl = $nextTrack?->preview_url;
            $nextTrackAudioUrl = $nextTrack?->audio;
        }

        $payload = [
            'round' => $this->round->toArray(),
            'next_track_at' => $isLastTrack
                ? null
                : now()->addSeconds((int) $room->pause_between_tracks)->toIso8601String(),
            'next_track_id' => $nextTrackId,
            'next_track_preview_url' => $nextTrackPreviewUrl,
            'next_track_audio_url' => $nextTrackAudioUrl,
        ];

        if ($this->track !== null) {
            $payload['track'] = app(TrackAnswerCacheService::class)->playlistPayloadForRoom($this->track);
        }

        return $payload;
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
            return Track::query()->findOrFail($this->round->tracks[$current]);
        } catch (ModelNotFoundException $e) {
            Log::error('Track not found', ['track_id' => $this->round->tracks[$current]]);

            return null;
        }
    }
}
