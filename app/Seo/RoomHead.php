<?php

namespace App\Seo;

use App\Models\Room;
use Laravel\Head\Facades\Head;

class RoomHead
{
    public function apply(Room $room, int $roundsCount, bool $isPasswordProtected = false): void
    {
        $description = $this->description($room);
        $title = __(':room — :suffix', [
            'room' => $room->name,
            'suffix' => __('Room title suffix'),
        ]);
        $image = $room->photo ?: url('images/statics/screenshot.png');
        $roomUrl = route('rooms.show', $room->slug);
        $fullTitle = "{$title} | Blinest";

        Head::title($title)
            ->description($description)
            ->canonical($roomUrl)
            ->when($isPasswordProtected, fn ($head) => $head->hiddenFromRobots())
            ->og(
                type: 'website',
                title: $fullTitle,
                description: $description,
                url: $roomUrl,
                image: $image,
                siteName: 'Blinest',
                locale: $this->ogLocale(),
            )
            ->ogImage(
                $image,
                alt: __('Room OG image alt', ['room' => $room->name]),
                width: 1200,
                height: 630,
            )
            ->twitter(
                card: 'summary_large_image',
                site: '@blinest',
                creator: '@blinest',
                title: $fullTitle,
                description: $description,
                image: $image,
            )
            ->twitterImage($image, alt: __('Room OG image alt', ['room' => $room->name]))
            ->schema($this->structuredData($room));
    }

    /**
     * @return array<string, mixed>
     */
    protected function structuredData(Room $room): array
    {
        $description = $room->description ?: __('Room schema default description', ['room' => $room->name]);

        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'VideoGame',
            'name' => $room->name,
            'description' => $description,
            'url' => route('rooms.show', $room->slug),
            'image' => $room->photo ?: url('/images/statics/logo_blinest.png'),
            'gamePlatform' => 'Web Browser',
            'applicationCategory' => 'Game',
            'genre' => $room->category?->name ?: 'Music Quiz',
            'gameItem' => [
                '@type' => 'Thing',
                'name' => __('Room schema game item name'),
                'description' => __('Room schema game item description'),
            ],
            'offers' => [
                '@type' => 'Offer',
                'price' => '0',
                'priceCurrency' => 'EUR',
                'availability' => 'https://schema.org/InStock',
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'Blinest',
                'url' => config('app.url'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => url('/images/statics/logo_blinest.png'),
                ],
            ],
            'inLanguage' => ['fr', 'en', 'es'],
            'isAccessibleForFree' => true,
            'playMode' => 'MultiPlayer',
            'numberOfPlayers' => [
                'minValue' => 1,
                'maxValue' => 100,
            ],
            'datePublished' => $room->created_at?->toIso8601String(),
            'dateModified' => $room->updated_at?->toIso8601String(),
            'mainEntity' => [
                '@type' => 'Thing',
                'name' => __('Room schema main entity name'),
                'description' => __('Room schema main entity description'),
            ],
        ];

        if ($room->owner) {
            $structuredData['author'] = [
                '@type' => 'Person',
                'name' => $room->owner->name,
            ];
        }

        return $structuredData;
    }

    protected function description(Room $room): string
    {
        if ($room->description) {
            return mb_strlen($room->description) > 160
                ? mb_substr($room->description, 0, 157).'...'
                : $room->description;
        }

        return __('Room default description', ['room' => $room->name]);
    }

    protected function ogLocale(): string
    {
        return match (app()->getLocale()) {
            'en' => 'en_US',
            'es' => 'es_ES',
            default => 'fr_FR',
        };
    }
}
