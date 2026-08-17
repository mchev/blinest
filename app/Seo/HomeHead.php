<?php

namespace App\Seo;

use Laravel\Head\Facades\Head;

class HomeHead
{
    public function apply(): void
    {
        $localeUrl = app(LocaleUrl::class);

        Head::title(__('Free multiplayer music quizzes'))
            ->description(__('Home meta description'))
            ->canonical($localeUrl->canonical('/'))
            ->alternates($localeUrl->alternates('/'))
            ->meta('keywords', __('Home meta keywords'))
            ->schema($this->websiteSchema());
    }

    public function applyForSearch(): void
    {
        $localeUrl = app(LocaleUrl::class);

        Head::title(__('Search Results'))
            ->description(__('Home search meta description'))
            ->canonical($localeUrl->canonical('/'))
            ->alternates($localeUrl->alternates('/'))
            ->hiddenFromRobots()
            ->schema($this->websiteSchema());
    }

    /**
     * @return array<string, mixed>
     */
    private function websiteSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'url' => rtrim(config('app.url'), '/'),
            'name' => 'Blinest',
            'description' => __('Home schema description'),
            'inLanguage' => LocaleUrl::availableLocales(),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => rtrim(config('app.url'), '/').'/?search={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }
}
