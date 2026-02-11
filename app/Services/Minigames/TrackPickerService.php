<?php

namespace App\Services\Minigames;

use App\Models\MinigameScore;
use App\Models\Track;
use App\Models\TrackAnswer;
use Illuminate\Support\Collection;

class TrackPickerService
{
    private const ANSWER_TYPE_ARTIST = 1;

    private const ANSWER_TYPE_TITLE = 2;

    private const MAX_RANDOM_RETRIES = 5;

    /**
     * Pick a random track and 4 choices (1 correct + 3 wrong) for the given game type.
     * Uses random-ID range queries to avoid slow ORDER BY RAND() on large tables.
     *
     * @return array{track: array<string, mixed>, choices: array<int, string>}
     */
    public function getRandomTrackWithChoices(string $gameType): array
    {
        $requireArtwork = $gameType === MinigameScore::TYPE_ALBUM_COVER;
        $answerTypeId = ($gameType === MinigameScore::TYPE_WHO_SANG || $gameType === MinigameScore::TYPE_ALBUM_COVER)
            ? self::ANSWER_TYPE_ARTIST
            : self::ANSWER_TYPE_TITLE;

        $track = $this->pickRandomTrackWithAnswer($answerTypeId, $requireArtwork);
        if (! $track) {
            throw new \RuntimeException(__('No track available for this minigame.'));
        }

        $correctAnswer = $track->answers
            ->firstWhere('answer_type_id', $answerTypeId);
        $correctValue = $correctAnswer?->value ?? '';

        $wrongValues = $this->getWrongChoices($track->id, $answerTypeId, $correctValue, 3);
        $choices = collect([$correctValue])
            ->merge($wrongValues)
            ->shuffle()
            ->values()
            ->all();

        return [
            'track' => $this->formatTrackForFront($track),
            'choices' => $choices,
            'correct_value' => $correctValue,
        ];
    }

    /**
     * Pick a random track with title only (for free-text games like "first letter").
     *
     * @return array{track: array<string, mixed>, correct_value: string}
     */
    public function getRandomTrackWithTitle(): array
    {
        $track = $this->pickRandomTrackWithAnswer(self::ANSWER_TYPE_TITLE, false);
        if (! $track) {
            throw new \RuntimeException(__('No track available for this minigame.'));
        }

        $rawTitle = $track->answers->firstWhere('answer_type_id', self::ANSWER_TYPE_TITLE)?->value ?? '';

        return [
            'track' => $this->formatTrackForFront($track),
            'correct_value' => sanitizeString($rawTitle),
        ];
    }

    /**
     * Pick a random entry from the predefined anagram list (database/data/anagram_artists.json).
     * No database lookup: the game only needs the anagram and the correct artist name.
     *
     * @return array{scrambled_artist: string, artist: string}
     */
    public function getRandomArtistAnagram(): array
    {
        $list = $this->loadAnagramList();
        if (empty($list)) {
            throw new \RuntimeException(__('No track available for this minigame.'));
        }

        $entry = $list[array_rand($list)];

        return [
            'scrambled_artist' => $entry['anagram'],
            'artist' => $entry['artist'],
        ];
    }

    /**
     * Load anagram list from database/data/anagram_artists.json.
     *
     * @return array<int, array{artist: string, anagram: string}>
     */
    private function loadAnagramList(): array
    {
        $path = database_path('data/anagram_artists.json');
        if (! is_file($path)) {
            return [];
        }
        $json = file_get_contents($path);
        $data = json_decode($json, true);
        if (! is_array($data)) {
            return [];
        }

        return array_values(array_filter($data, fn ($row) => isset($row['artist'], $row['anagram'])));
    }

    /**
     * Pick a random track using id >= random_id to avoid ORDER BY RAND().
     * Only considers tracks from public (official) playlists.
     *
     * @param  bool  $requireArtwork  When true, only tracks with artwork_url (e.g. album cover game).
     */
    private function pickRandomTrackWithAnswer(int $answerTypeId, bool $requireArtwork = false): ?Track
    {
        $maxId = (int) Track::query()->max('id');
        if ($maxId < 1) {
            return null;
        }

        $audioCondition = fn ($q) => $q->where('tracks.provider', '!=', 'youtube')
            ->where(function ($q) {
                $q->whereNotNull('tracks.preview_url')
                    ->orWhereIn('tracks.provider', ['spotify', 'itunes', 'deezer', 'audius']);
            });

        $baseQuery = fn () => Track::query()
            ->join('playlists', 'playlists.id', '=', 'tracks.playlist_id')
            ->where('playlists.is_public', true)
            ->join('track_answers', function ($join) use ($answerTypeId) {
                $join->on('track_answers.track_id', '=', 'tracks.id')
                    ->where('track_answers.answer_type_id', '=', $answerTypeId);
            })
            ->when($requireArtwork, fn ($q) => $q->whereNotNull('tracks.artwork_url'))
            ->where($audioCondition)
            ->select('tracks.*');

        for ($i = 0; $i < self::MAX_RANDOM_RETRIES; $i++) {
            $randomId = random_int(1, $maxId);
            $track = (clone $baseQuery())
                ->where('tracks.id', '>=', $randomId)
                ->orderBy('tracks.id')
                ->first();

            if ($track !== null) {
                $track->load('answers');

                return $track;
            }
        }

        return $baseQuery()->limit(1)->first()?->load('answers');
    }

    /**
     * Wrong choices only from tracks in public playlists.
     *
     * @return Collection<int, string>
     */
    private function getWrongChoices(int $excludeTrackId, int $answerTypeId, string $excludeValue, int $count): Collection
    {
        $query = TrackAnswer::query()
            ->join('tracks', 'tracks.id', '=', 'track_answers.track_id')
            ->join('playlists', 'playlists.id', '=', 'tracks.playlist_id')
            ->where('playlists.is_public', true)
            ->where('track_answers.answer_type_id', $answerTypeId)
            ->where('track_answers.track_id', '!=', $excludeTrackId)
            ->where('track_answers.value', '!=', $excludeValue)
            ->whereNotNull('track_answers.value');

        $maxId = (int) (clone $query)->max('track_answers.id');
        if ($maxId < 1) {
            return collect();
        }

        $candidates = collect();
        for ($i = 0; $i < 3; $i++) {
            $randomId = random_int(1, max(1, $maxId));
            $batch = (clone $query)
                ->where('track_answers.id', '>=', $randomId)
                ->orderBy('track_answers.id')
                ->limit(5)
                ->pluck('track_answers.value');
            $candidates = $candidates->merge($batch);
        }

        return $candidates->unique()->values()->take($count);
    }

    /**
     * @return array<string, mixed>
     */
    private function formatTrackForFront(Track $track): array
    {
        return [
            'id' => $track->id,
            'preview_url' => $track->audio,
            'artwork_url' => $track->artwork_url,
        ];
    }
}
