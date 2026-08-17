<?php

namespace App\Http\Controllers\Minigames;

use App\Http\Controllers\Controller;
use App\Models\MinigameScore;
use App\Seo\MinigameHead;
use App\Services\Minigames\MinigameScoreService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MinigameController extends Controller
{
    public function __construct(private MinigameHead $minigameHead) {}

    /**
     * Build the list of minigames with play URLs and scores (for index and home slider).
     *
     * @param  array<string, int>  $scoresByType  Score total per game type (e.g. from MinigameScoreService).
     * @return array<int, array{type: string, name: string, description: string, play_url: string, score: int}>
     */
    public static function buildGamesList(array $scoresByType): array
    {
        $labels = MinigameScore::typeLabels();

        return [
            [
                'type' => MinigameScore::TYPE_QUIZ,
                'name' => $labels[MinigameScore::TYPE_QUIZ],
                'description' => __('Guess the track title from 4 choices.'),
                'play_url' => route('minigames.quiz.play'),
                'score' => $scoresByType[MinigameScore::TYPE_QUIZ] ?? 0,
            ],
            [
                'type' => MinigameScore::TYPE_WHO_SANG,
                'name' => $labels[MinigameScore::TYPE_WHO_SANG],
                'description' => __('Guess who sang this track from 4 choices.'),
                'play_url' => route('minigames.who_sang.play'),
                'score' => $scoresByType[MinigameScore::TYPE_WHO_SANG] ?? 0,
            ],
            [
                'type' => MinigameScore::TYPE_ANAGRAM,
                'name' => $labels[MinigameScore::TYPE_ANAGRAM],
                'description' => __('Unscramble the letters to find the artist name.'),
                'play_url' => route('minigames.anagram.play'),
                'score' => $scoresByType[MinigameScore::TYPE_ANAGRAM] ?? 0,
            ],
            [
                'type' => MinigameScore::TYPE_FIRST_LETTER,
                'name' => $labels[MinigameScore::TYPE_FIRST_LETTER],
                'description' => __('Listen and complete the title from the first letter of each word.'),
                'play_url' => route('minigames.first_letter.play'),
                'score' => $scoresByType[MinigameScore::TYPE_FIRST_LETTER] ?? 0,
            ],
            [
                'type' => MinigameScore::TYPE_ALBUM_COVER,
                'name' => __('Album cover'),
                'description' => __('Find the artist from the album cover.'),
                'play_url' => route('minigames.album_cover.play'),
                'score' => $scoresByType[MinigameScore::TYPE_ALBUM_COVER] ?? 0,
            ],
        ];
    }

    public function index(Request $request)
    {
        $this->minigameHead->applyIndex();

        $user = $request->user();
        $scoreService = app(MinigameScoreService::class);
        $scoresByType = $scoreService->getTotalsByTypeForUser($user);

        return Inertia::render('Minigames/Index', [
            'games' => self::buildGamesList($scoresByType),
        ]);
    }
}
