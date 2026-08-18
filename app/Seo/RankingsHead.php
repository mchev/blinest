<?php

namespace App\Seo;

use Laravel\Head\Facades\Head;

class RankingsHead
{
    /**
     * @param  list<array<string, mixed>>  $entries
     */
    public function apply(string $sort, array $entries): void
    {
        $localeUrl = app(LocaleUrl::class);
        $path = 'rankings';

        Head::title(__('Rankings'))
            ->description(__('Rankings meta description'))
            ->canonical($localeUrl->canonical($path))
            ->alternates($localeUrl->alternates($path))
            ->meta('keywords', __('Rankings meta keywords'))
            ->schema($this->itemListSchema($entries));
    }

    /**
     * @param  list<array<string, mixed>>  $entries
     * @return array<string, mixed>
     */
    protected function itemListSchema(array $entries): array
    {
        $localeUrl = app(LocaleUrl::class);

        return [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => __('Rankings schema name'),
            'description' => __('Rankings meta description'),
            'url' => $localeUrl->canonical('rankings'),
            'numberOfItems' => count($entries),
            'itemListElement' => collect($entries)
                ->take(10)
                ->values()
                ->map(fn (array $entry, int $index): array => [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'name' => $entry['user']['name'] ?? __('Player'),
                ])
                ->all(),
        ];
    }
}
