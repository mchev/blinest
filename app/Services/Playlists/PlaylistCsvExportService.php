<?php

namespace App\Services\Playlists;

use App\Models\AnswerType;
use App\Models\Playlist;
use App\Models\Track;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PlaylistCsvExportService
{
    /**
     * @return list<string>
     */
    public function headings(Collection $answerTypes): array
    {
        $headings = $answerTypes
            ->map(fn (AnswerType $type) => __($type->name))
            ->all();

        return [
            ...$headings,
            'Origine',
            'Lien',
            'Difficulté',
            'Vote +',
            'Vote -',
            'Ajouté le',
        ];
    }

    /**
     * @return list<int|string|null>
     */
    public function mapTrack(Track $track, Collection $answerTypes): array
    {
        $row = [];

        foreach ($answerTypes as $type) {
            $answers = $track->answers
                ->where('answer_type_id', $type->id)
                ->pluck('value');

            $row[] = implode(', ', $answers->all());
        }

        $row[] = $track->provider;
        $row[] = $track->provider_url;
        $row[] = $track->dificulty;
        $row[] = $track->totalUpvotes;
        $row[] = $track->totalDownvotes;
        $row[] = $track->created_at->format('d/m/y H:i');

        return $row;
    }

    public function download(Playlist $playlist): StreamedResponse
    {
        $filename = str($playlist->name)->slug()->append('.csv')->toString();

        return response()->streamDownload(function () use ($playlist): void {
            $handle = fopen('php://output', 'w');

            if ($handle === false) {
                return;
            }

            fwrite($handle, "\xEF\xBB\xBF");

            $answerTypes = AnswerType::query()->orderBy('id')->get();

            fputcsv($handle, $this->headings($answerTypes), ';');

            $playlist->tracks()
                ->with(['answers'])
                ->withTotalUpvotes()
                ->withTotalDownvotes()
                ->orderBy('id')
                ->chunkById(200, function ($tracks) use ($handle, $answerTypes): void {
                    foreach ($tracks as $track) {
                        fputcsv($handle, $this->mapTrack($track, $answerTypes), ';');
                    }
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
