<?php

namespace App\Jobs;

use App\Track;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreTrack implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user_id;
    protected $game_id;
    protected $provider_item_id;
    protected $provider;
    protected $artist_name;
    protected $track_name;
    protected $artwork_url;
    protected $preview_url;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_id, $game_id, $provider_item_id, $provider, $artist_name, $track_name, $artwork_url, $preview_url)
    {
        $this->user_id = $user_id;
        $this->game_id = $game_id;
        $this->provider_item_id = $provider_item_id;
        $this->provider = $provider;
        $this->artist_name = $artist_name;
        $this->track_name = $track_name;
        $this->artwork_url = $artwork_url;
        $this->preview_url = $preview_url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $track = Track::firstOrCreate(
            [
                'game_id' => $this->game_id, 
                'provider_item_id', $this->provider_item_id
            ],
            [
                'user_id' => $this->user_id,
                'provider' => $this->provider,
                'artist_name' => $this->artist_name,
                'track_name' => $this->track_name,
                'artwork_url' => $this->artwork_url,
                'preview_url' => $this->preview_url
            ]
        );
    }
}
