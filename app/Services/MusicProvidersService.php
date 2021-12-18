<?php

namespace App\Services;

use App\Services\MusicProviders\AppleMusicService;
use App\Services\MusicProviders\SpotifyService;
use App\Services\MusicProviders\DeezerService;

class MusicProvidersService
{

	protected $itunes;
	protected $deezer;
	protected $spotify;


	public function __construct()
	{
		$this->itunes = new AppleMusicService;
		$this->spotify = new SpotifyService;
		$this->deezer = new DeezerService;
	}

	public function search(String $term)
	{
		$itunesTracks = $this->itunes->search($term);
		$spotifyTracks = $this->spotify->search($term);
		$deezerTracks = $this->deezer->search($term);

		$merged = $itunesTracks->merge($spotifyTracks)->merge($deezerTracks);

		$sorted = $merged->sortBy('track_name')->unique(function($item) {
			return $item['artist_name'].$item['track_name'];
		});

		return $sorted->values()->all();;
	}

}
