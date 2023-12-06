<?php

namespace App\Exports;

use App\Models\AnswerType;
use App\Models\Playlist;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PlaylistExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping
{
    protected $playlist;

    protected $answersTypes;

    public function __construct(Playlist $playlist)
    {
        $this->playlist = $playlist;
        $this->answersTypes = AnswerType::orderBy('id')->get();
    }

    public function query()
    {
        return $this->playlist->tracks()
            ->with(['answers']) // Eager load relationships
            ->orderBy('id'); // Make sure you have an appropriate order for chunking
    }

    public function map($track): array
    {
        $row = [];

        foreach ($this->answersTypes as $type) {
            $answers = $track->answers->where('answer_type_id', $type->id)->pluck('value');
            $row[] = implode(', ', $answers->toArray());
        }

        $row[] = $track->provider;
        $row[] = $track->provider_url;
        $row[] = $track->dificulty;
        $row[] = $track->totalUpvotes;
        $row[] = $track->totalDownvotes;
        $row[] = $track->created_at->format('d/m/y H:i');

        return $row;
    }

    public function headings(): array
    {
        // Generate headings for the columns
        $headings = [];

        foreach ($this->answersTypes as $type) {
            $headings[] = __($type->name);
        }

        $headings[] = 'Origine';
        $headings[] = 'Lien';
        $headings[] = 'Difficulté';
        $headings[] = 'Vote +';
        $headings[] = 'Vote -';
        $headings[] = 'Ajouté le';

        return $headings;
    }
}
