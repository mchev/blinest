<?php

namespace App\Services\Tracks;

use App\Models\AnswerType;
use App\Models\Track;
use App\Models\TrackAnswer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class TrackAnswerCacheService
{
    /**
     * @return Collection<int, TrackAnswer>
     */
    public function answersForTrack(Track $track): Collection
    {
        $cached = Cache::rememberForever(Track::answersCacheKey($track->id), function () use ($track) {
            return $this->serializeForCache(
                $track->answers()->with('type:id,name')->get()
            );
        });

        return $this->hydrateFromCache($cached);
    }

    /**
     * @return array<string, mixed>
     */
    public function playlistPayloadForRoom(Track $track): array
    {
        $answers = $this->answersForTrack($track);

        return [
            'id' => $track->id,
            'provider' => $track->provider,
            'preview_url' => $track->preview_url,
            'artwork_url' => $track->artwork_url,
            'album_name' => null,
            'hint' => $track->hint,
            'upvotes' => $track->upvotes,
            'downvotes' => $track->downvotes,
            'answers' => $answers->map(fn (TrackAnswer $answer) => [
                'id' => $answer->id,
                'value' => $answer->value,
                'type' => [
                    'name' => $answer->type->name,
                ],
            ])->all(),
        ];
    }

    /**
     * @return list<array{id: int, value: string|null, aliases: array|null, score: float, answer_type_id: int, type_name: string}>
     */
    private function serializeForCache(Collection $answers): array
    {
        return $answers
            ->map(fn (TrackAnswer $answer) => [
                'id' => $answer->id,
                'value' => $answer->value,
                'aliases' => $answer->aliases,
                'score' => $answer->score,
                'answer_type_id' => $answer->answer_type_id,
                'type_name' => $answer->type->name,
            ])
            ->values()
            ->all();
    }

    /**
     * @param  list<array{id: int, value: string|null, aliases: array|null, score: float, answer_type_id: int, type_name: string}>  $cached
     * @return Collection<int, TrackAnswer>
     */
    private function hydrateFromCache(array $cached): Collection
    {
        return collect($cached)->map(function (array $data): TrackAnswer {
            $answer = (new TrackAnswer)->forceFill([
                'id' => $data['id'],
                'value' => $data['value'],
                'aliases' => $data['aliases'],
                'score' => $data['score'],
                'answer_type_id' => $data['answer_type_id'],
            ]);
            $answer->exists = true;
            $answer->setRelation('type', (new AnswerType)->forceFill([
                'id' => $data['answer_type_id'],
                'name' => $data['type_name'],
            ]));

            return $answer;
        });
    }
}
