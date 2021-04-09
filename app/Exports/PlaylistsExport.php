<?php

namespace App\Exports;

use App\Game;
use App\Track;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PlaylistsExport implements FromCollection, ShouldAutoSize
{
    protected $game;

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    public function collection()
    {
        return $this->game->tracks;
    }
}
