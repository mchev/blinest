<?php

namespace App\Http\Controllers\Minigames;

use App\Http\Controllers\Controller;
use App\Models\MinigameScore;
use App\Services\Minigames\MinigameScoreService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MinigameController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $scoreService = app(MinigameScoreService::class);

        $scoresByType = $scoreService->getTotalsByTypeForUser($user);

        return Inertia::render('Minigames/Index', [
            'games' => [
                [
                    'type' => MinigameScore::TYPE_QUIZ,
                    'name' => MinigameScore::typeLabels()[MinigameScore::TYPE_QUIZ],
                    'description' => __('Guess the track title from 4 choices.'),
                    'play_url' => route('minigames.quiz.play'),
                    'score' => $scoresByType[MinigameScore::TYPE_QUIZ],
                ],
                [
                    'type' => MinigameScore::TYPE_WHO_SANG,
                    'name' => MinigameScore::typeLabels()[MinigameScore::TYPE_WHO_SANG],
                    'description' => __('Guess who sang this track from 4 choices.'),
                    'play_url' => route('minigames.who_sang.play'),
                    'score' => $scoresByType[MinigameScore::TYPE_WHO_SANG],
                ],
                [
                    'type' => MinigameScore::TYPE_ALBUM_COVER,
                    'name' => __('Album cover'),
                    'description' => __('Find the artist from the album cover.'),
                    'play_url' => route('minigames.album_cover.play'),
                    'score' => $scoresByType[MinigameScore::TYPE_ALBUM_COVER],
                ],
            ],
        ]);
    }
}
