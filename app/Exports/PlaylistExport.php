<?php

namespace App\Exports;

use App\Models\AnswerType;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PlaylistExport implements FromView, ShouldAutoSize
{
    public function __construct(public $tracks)
    {
    }

    public function view(): View
    {        
        return view('exports.playlists', [
            'tracks' => $this->tracks,
            'answer_types' => AnswerType::orderBy('id')->get(),
        ]);
    }
}
