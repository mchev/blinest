<?php

namespace App\Http\Controllers\Minigames;

use App\Http\Controllers\Controller;
use App\Models\MinigameScore;
use App\Models\Track;
use App\Services\Minigames\MinigameScoreService;
use App\Services\Minigames\TrackPickerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class FirstLetterController extends Controller
{
    private const POINTS_CORRECT = 1;

    public function play(Request $request)
    {
        return Inertia::render('Minigames/FirstLetter/Play', [
            'gameType' => MinigameScore::TYPE_FIRST_LETTER,
        ]);
    }

    public function next(Request $request): JsonResponse
    {
        $picker = app(TrackPickerService::class);

        try {
            $data = $picker->getRandomTrackWithTitle();
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
        $correctTitle = $track->answers->firstWhere('answer_type_id', 2)?->value;
        $given = trim((string) $request->input('chosen_value'));
        $isCorrect = $correctTitle !== null
            && $given !== ''
            && Str::slug($given) === Str::slug($correctTitle);

        if ($isCorrect) {
            app(MinigameScoreService::class)->record(
                $request->user(),
                MinigameScore::TYPE_FIRST_LETTER,
                self::POINTS_CORRECT,
                ['track_id' => $track->id]
            );
        }

        return response()->json([
            'correct' => $isCorrect,
            'points' => $isCorrect ? self::POINTS_CORRECT : 0,
            'correct_value' => $correctTitle,
        ]);
    }
}
