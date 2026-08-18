<?php

namespace App\Services\Rooms;

use App\Models\Room;

class RoomContentService
{
    /**
     * @param  array{rounds: int, tracks: int, players_online: int}  $stats
     * @return array{
     *     intro: string,
     *     intro_secondary: string,
     *     faq: list<array{question: string, answer: string}>
     * }
     */
    public function forRoom(Room $room, array $stats): array
    {
        $replace = $this->replaceTokens($room, $stats);

        return [
            'intro' => $this->intro($room, $replace),
            'intro_secondary' => $this->introSecondary($room, $replace),
            'faq' => [
                [
                    'question' => __('Room page FAQ q1', $replace),
                    'answer' => __('Room page FAQ a1', $replace),
                ],
                [
                    'question' => __('Room page FAQ q2', $replace),
                    'answer' => __('Room page FAQ a2', $replace),
                ],
            ],
        ];
    }

    public function metaDescription(Room $room, array $stats): string
    {
        $replace = $this->replaceTokens($room, $stats);
        $configured = $this->configuredText($room->slug, 'intro', app()->getLocale());

        if ($configured !== null) {
            return mb_strlen($configured) > 160
                ? mb_substr($configured, 0, 157).'...'
                : $configured;
        }

        return __('Room page meta description', $replace);
    }

    /**
     * @param  array<string, string|int>  $replace
     */
    private function intro(Room $room, array $replace): string
    {
        $configured = $this->configuredText($room->slug, 'intro', app()->getLocale());

        if ($configured !== null) {
            return $this->interpolate($configured, $replace);
        }

        $categorySlug = $room->category?->slug;

        if ($categorySlug !== null) {
            $categoryKey = "Room category {$categorySlug} intro";
            $categoryText = __($categoryKey, $replace);

            if ($categoryText !== $categoryKey) {
                return $categoryText;
            }
        }

        return __('Room page intro', $replace);
    }

    /**
     * @param  array<string, string|int>  $replace
     */
    private function introSecondary(Room $room, array $replace): string
    {
        $configured = $this->configuredText($room->slug, 'intro_secondary', app()->getLocale());

        if ($configured !== null) {
            return $this->interpolate($configured, $replace);
        }

        return __('Room page intro secondary', $replace);
    }

    /**
     * @param  array{rounds: int, tracks: int, players_online: int}  $stats
     * @return array<string, string|int>
     */
    private function replaceTokens(Room $room, array $stats): array
    {
        return [
            'room' => $room->name,
            'category' => $room->category ? __($room->category->name) : '',
            'rounds' => $stats['rounds'],
            'tracks' => $stats['tracks'],
            'players' => $stats['players_online'],
        ];
    }

    private function configuredText(string $slug, string $field, string $locale): ?string
    {
        $text = config("official_room_seo.intros.{$slug}.{$locale}.{$field}");

        if (! is_string($text) || $text === '') {
            return null;
        }

        return $text;
    }

    /**
     * @param  array<string, string|int>  $replace
     */
    private function interpolate(string $text, array $replace): string
    {
        foreach ($replace as $key => $value) {
            $text = str_replace(':'.$key, (string) $value, $text);
        }

        return $text;
    }
}
