<?php

namespace App\Seo;

use App\Models\FAQ;
use Illuminate\Support\Collection;
use Laravel\Head\Facades\Head;

class FaqHead
{
    /**
     * @param  Collection<int, FAQ>  $faqs
     */
    public function apply(Collection $faqs): void
    {
        Head::title(__('FAQ'))
            ->description(__('FAQ meta description'))
            ->canonical(app(LocaleUrl::class)->canonical('docs/faq'))
            ->alternates(app(LocaleUrl::class)->alternates('docs/faq'));

        if ($faqs->isEmpty()) {
            return;
        }

        Head::schema([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $faqs->map(fn ($faq) => [
                '@type' => 'Question',
                'name' => $faq->question,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => strip_tags($faq->answer),
                ],
            ])->values()->all(),
        ]);
    }
}
