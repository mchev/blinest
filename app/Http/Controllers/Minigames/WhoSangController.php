<?php

namespace App\Http\Controllers\Minigames;

use App\Http\Controllers\Controller;
use App\Models\MinigameScore;
use App\Models\Track;
use App\Seo\MinigameHead;
use App\Services\Minigames\MinigameScoreService;
use App\Services\Minigames\TrackPickerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WhoSangController extends Controller
{
    public function __construct(private MinigameHead $minigameHead) {}

    private const POINTS_CORRECT = 1;

    public function play(Request $request)
    {
        $this->minigameHead->applyPlay(MinigameScore::TYPE_WHO_SANG);

        return Inertia::render('Minigames/WhoSang/Play', [
            'gameType' => MinigameScore::TYPE_WHO_SANG,
        ]);
    }

    public function next(Request $request): JsonResponse
    {
        $picker = app(TrackPickerService::class);

        try {
            $data = $picker->getRandomTrackWithChoices(MinigameScore::TYPE_WHO_SANG);
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
        $correctArtist = $track->answers->firstWhere('answer_type_id', 1)?->value;
        $isCorrect = $correctArtist !== null && trim((string) $request->input('chosen_value')) === trim($correctArtist);

        if ($isCorrect) {
            app(MinigameScoreService::class)->record(
                $request->user(),
                MinigameScore::TYPE_WHO_SANG,
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
