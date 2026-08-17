<?php

namespace App\Seo;

use Laravel\Head\Facades\Head;

class DocsHead
{
    public function applyOverview(): void
    {
        $this->applyPage('docs', fn ($head) => $head
            ->title(__('Docs overview title'))
            ->description(__('Docs overview description')));
    }

    public function applyLevel(): void
    {
        $this->applyPage('docs/level', fn ($head) => $head
            ->title(__('Level System'))
            ->description(__('Docs level description')));
    }

    public function applyElo(): void
    {
        $this->applyPage('docs/elo', fn ($head) => $head
            ->title(__('Docs elo title'))
            ->description(__('Docs elo description')));
    }

    public function applyHowTo(): void
    {
        $this->applyPage('docs/howto', function ($head): void {
            $head
                ->title(__('Docs howto title'))
                ->description(__('Docs howto description'))
                ->meta('keywords', __('Docs howto keywords'))
                ->schema($this->howToSchema(rtrim(config('app.url'), '/')));
        });
    }

    public function applyGlossary(): void
    {
        $this->applyPage('docs/glossary', function ($head): void {
            $head
                ->title(__('Docs glossary title'))
                ->description(__('Docs glossary description'))
                ->meta('keywords', __('Docs glossary keywords'))
                ->schema($this->glossarySchema(rtrim(config('app.url'), '/')));
        });
    }

    public function applyCreateContent(): void
    {
        $this->applyPage('docs/create-content', fn ($head) => $head
            ->title(__('Docs create content title'))
            ->description(__('Docs create content description'))
            ->meta('keywords', __('Docs create content keywords')));
    }

    /**
     * @param  callable(HeadBuilder): void  $configure
     */
    protected function applyPage(string $path, callable $configure): void
    {
        $localeUrl = app(LocaleUrl::class);

        $configure(Head::canonical($localeUrl->canonical($path))
            ->alternates($localeUrl->alternates($path)));
    }

    /**
     * @return array<string, mixed>
     */
    protected function howToSchema(string $appUrl): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'HowTo',
            'name' => __('Docs howto schema name'),
            'description' => __('Docs howto schema description'),
            'image' => $appUrl.'/images/statics/screenshot.png',
            'totalTime' => 'PT5M',
            'estimatedCost' => [
                '@type' => 'MonetaryAmount',
                'currency' => 'EUR',
                'value' => '0',
            ],
            'supply' => [
                ['@type' => 'HowToSupply', 'name' => __('Docs howto supply account')],
                ['@type' => 'HowToSupply', 'name' => __('Docs howto supply internet')],
                ['@type' => 'HowToSupply', 'name' => __('Docs howto supply audio')],
            ],
            'tool' => [
                ['@type' => 'HowToTool', 'name' => __('Docs howto tool browser')],
            ],
            'step' => [
                [
                    '@type' => 'HowToStep',
                    'position' => 1,
                    'name' => __('Docs howto step 1 name'),
                    'text' => __('Docs howto step 1 text'),
                    'url' => $appUrl.'/register',
                ],
                [
                    '@type' => 'HowToStep',
                    'position' => 2,
                    'name' => __('Docs howto step 2 name'),
                    'text' => __('Docs howto step 2 text'),
                    'url' => $appUrl.'/',
                ],
                [
                    '@type' => 'HowToStep',
                    'position' => 3,
                    'name' => __('Docs howto step 3 name'),
                    'text' => __('Docs howto step 3 text'),
                ],
                [
                    '@type' => 'HowToStep',
                    'position' => 4,
                    'name' => __('Docs howto step 4 name'),
                    'text' => __('Docs howto step 4 text'),
                ],
                [
                    '@type' => 'HowToStep',
                    'position' => 5,
                    'name' => __('Docs howto step 5 name'),
                    'text' => __('Docs howto step 5 text'),
                ],
                [
                    '@type' => 'HowToStep',
                    'position' => 6,
                    'name' => __('Docs howto step 6 name'),
                    'text' => __('Docs howto step 6 text'),
                ],
                [
                    '@type' => 'HowToStep',
                    'position' => 7,
                    'name' => __('Docs howto step 7 name'),
                    'text' => __('Docs howto step 7 text'),
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function glossarySchema(string $appUrl): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'DefinedTermSet',
            'name' => __('Docs glossary schema name'),
            'description' => __('Docs glossary schema description'),
            'url' => $appUrl.'/docs/glossary',
            'dateModified' => now()->toDateString(),
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'Blinest',
                'url' => $appUrl,
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => $appUrl.'/images/statics/logo_blinest.png',
                ],
            ],
            'inLanguage' => ['fr', 'en', 'es'],
        ];
    }
}
