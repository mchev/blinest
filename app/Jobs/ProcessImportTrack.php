<?php

namespace App\Jobs;

use App\Models\Playlist;
use App\Models\Track;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessImportTrack implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Playlist $playlist, 
        public object $track
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): ?Track
    {
        if ($this->track->preview_url && $this->track->artwork_url) {
            if ($this->playlist->tracks()->where('provider', $this->track->provider)->where('provider_id', $this->track->provider_id)->doesntExist()) {
                $track = $this->playlist->tracks()->create([
                    'provider' => $this->track->provider,
                    'provider_id' => $this->track->provider_id,
                    'provider_url' => $this->track->provider_url,
                    'preview_url' => $this->track->preview_url,
                    'artwork_url' => $this->track->artwork_url,
                ]);
                $artist = $track->answers()->create([
                    'answer_type_id' => 1,
                    'value' => $this->track->artist_name,
                ]);
                $title = $track->answers()->create([
                    'answer_type_id' => 2,
                    'value' => $this->track->track_name,
                ]);
            }
        }

        return (isset($track)) ? $track : null;
    }
}
