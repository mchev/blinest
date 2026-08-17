<?php

namespace App\Seo;

use App\Models\MinigameScore;
use Laravel\Head\Facades\Head;

class MinigameHead
{
    public function applyIndex(): void
    {
        Head::title(__('Mini-games'))
            ->description(__('Mini-games meta description'))
            ->meta('keywords', __('Mini-games meta keywords'));
    }

    public function applyPlay(string $gameType): void
    {
        [$title, $description] = match ($gameType) {
            MinigameScore::TYPE_QUIZ => [__('Quiz — 4 choices'), __('Quiz 4 choices meta description')],
            MinigameScore::TYPE_WHO_SANG => [__('Who sang?'), __('Who sang meta description')],
            MinigameScore::TYPE_ANAGRAM => [__('Anagram'), __('Anagram meta description')],
            MinigameScore::TYPE_FIRST_LETTER => [__('First letter'), __('First letter meta description')],
            MinigameScore::TYPE_ALBUM_COVER => [__('Album cover'), __('Album cover meta description')],
            default => [__('Mini-games'), __('Mini-games meta description')],
        };

        Head::title($title)->description($description);
    }
}
