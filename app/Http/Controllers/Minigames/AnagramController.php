<?php

namespace App\Http\Controllers\Minigames;

use App\Http\Controllers\Controller;
use App\Models\MinigameScore;
use App\Models\Track;
use App\Services\Minigames\MinigameScoreService;
use App\Services\Minigames\TrackPickerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

    public function next(Request $request): JsonResponse
    {
        $picker = app(TrackPickerService::class);

        try {
            $data = $picker->getRandomArtistAnagram();
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return response()->json($data);
    }

    public function check(Request $request): JsonResponse
    {
        $request->validate([
            'track_id' => 'required|integer|exists:tracks,id',
            'chosen_value' => 'required|string|max:500',
        ]);

        $track = Track::query()->with('answers')->findOrFail($request->input('track_id'));
        $rawArtist = $track->answers->firstWhere('answer_type_id', 1)?->value;
        $correctArtist = $rawArtist !== null ? TrackPickerService::stripParentheses($rawArtist) : null;
        $given = trim((string) $request->input('chosen_value'));
        $isCorrect = $correctArtist !== null && $correctArtist !== '' && strcasecmp($given, $correctArtist) === 0;

        if ($isCorrect) {
            app(MinigameScoreService::class)->record(
                $request->user(),
                MinigameScore::TYPE_ANAGRAM,
                self::POINTS_CORRECT,
                ['track_id' => $track->id]
            );
        }

        return response()->json([
            'correct' => $isCorrect,
            'points' => $isCorrect ? self::POINTS_CORRECT : 0,
            'correct_value' => $correctArtist,
        ]);
    }
}
