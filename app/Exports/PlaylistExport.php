<?php

namespace App\Exports;

use App\Models\Playlist;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PlaylistExport implements FromView, ShouldAutoSize
{
    public function __construct(public Playlist $playlist)
    {
    }

    public function view(): View
    {
        return view('exports.playlists', [
            'playlist' => $this->playlist,
        ]);
    }
}
