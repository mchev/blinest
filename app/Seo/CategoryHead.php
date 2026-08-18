<?php

namespace App\Seo;

use App\Models\Category;
use App\Services\Categories\CategoryContentService;
use Laravel\Head\Facades\Head;

class CategoryHead
{
    public function apply(Category $category, array $rooms, CategoryContentService $content): void
    {
        $localeUrl = app(LocaleUrl::class);
        $path = $category->landingPath();
        $title = $content->metaTitle($category);
        $description = $content->metaDescription($category, count($rooms));
        $label = __($category->name);

        Head::title($title)
            ->description($description)
            ->canonical($localeUrl->canonical($path))
            ->alternates($localeUrl->alternates($path))
            ->meta('keywords', __('Category page meta keywords', ['category' => $label]))
            ->schema($this->collectionPageSchema($category, $rooms, $localeUrl->canonical($path), $content));
    }

    /**
     * @param  list<array<string, mixed>>  $rooms
     * @return array<string, mixed>
     */
    protected function collectionPageSchema(Category $category, array $rooms, string $pageUrl, CategoryContentService $content): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $content->schemaName($category),
            'description' => $content->metaDescription($category, count($rooms)),
            'url' => $pageUrl,
            'inLanguage' => LocaleUrl::availableLocales(),
            'isPartOf' => [
                '@type' => 'WebSite',
                'name' => 'Blinest',
                'url' => rtrim(config('app.url'), '/'),
            ],
            'mainEntity' => [
                '@type' => 'ItemList',
                'name' => $content->forCategory($category, $rooms)['rooms_heading'],
                'numberOfItems' => count($rooms),
                'itemListElement' => collect($rooms)
                    ->take(20)
                    ->values()
                    ->map(fn (array $room, int $index): array => [
                        '@type' => 'ListItem',
                        'position' => $index + 1,
                        'url' => route('rooms.show', $room['slug']),
                        'name' => $room['name'],
                    ])
                    ->all(),
            ],
        ];
    }
}
