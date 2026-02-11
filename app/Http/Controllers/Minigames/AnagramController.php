<?php

namespace App\Http\Controllers\Minigames;

use App\Http\Controllers\Controller;
use App\Models\MinigameScore;
use App\Services\Minigames\MinigameScoreService;
use App\Services\Minigames\TrackPickerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AnagramController extends Controller
{
    private const POINTS_CORRECT = 1;

    public function play(Request $request)
    {
        return Inertia::render('Minigames/Anagram/Play', [
            'gameType' => MinigameScore::TYPE_ANAGRAM,
        ]);
    }

    private const QUESTION_CACHE_TTL_SECONDS = 300;

    public function next(Request $request): JsonResponse
    {
        $picker = app(TrackPickerService::class);

        try {
            $data = $picker->getRandomArtistAnagram();
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        $questionId = Str::uuid()->toString();
        Cache::put('anagram_question_'.$questionId, $data['artist'], self::QUESTION_CACHE_TTL_SECONDS);

        return response()->json([
            'question_id' => $questionId,
            'scrambled_artist' => $data['scrambled_artist'],
        ]);
    }

    public function check(Request $request): JsonResponse
    {
        $request->validate([
            'question_id' => 'required|string|uuid',
            'chosen_value' => 'required|string|max:500',
        ]);

        $questionId = $request->input('question_id');
        $correctArtist = Cache::pull('anagram_question_'.$questionId);
        if ($correctArtist === null) {
            return response()->json(['error' => __('Question expired or already answered.')], 422);
        }

        $given = trim((string) $request->input('chosen_value'));
        $isCorrect = strcasecmp($given, $correctArtist) === 0;

        if ($isCorrect) {
            app(MinigameScoreService::class)->record(
                $request->user(),
                MinigameScore::TYPE_ANAGRAM,
                self::POINTS_CORRECT,
                []
            );
        }

        return response()->json([
            'correct' => $isCorrect,
            'points' => $isCorrect ? self::POINTS_CORRECT : 0,
            'correct_value' => $correctArtist,
        ]);
    }
}
