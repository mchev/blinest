<?php

namespace App\Exports;

use App\Models\AnswerType;
use App\Models\Playlist;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
// use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PlaylistExport implements FromView
{
    public function __construct(public Playlist $playlist)
    {
        //
    }

    public function view(): View
    {
        return view('exports.playlists', [
            'tracks' => $this->playlist->tracks()->get(),
            'answer_types' => AnswerType::orderBy('id')->get(),
        ]);
    }
}
