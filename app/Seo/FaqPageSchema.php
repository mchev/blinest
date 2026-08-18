<?php

namespace App\Seo;

class FaqPageSchema
{
    /**
     * @param  list<array{question: string, answer: string}>  $faq
     * @return array<string, mixed>
     */
    public static function build(array $faq): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => collect($faq)
                ->map(fn (array $item): array => [
                    '@type' => 'Question',
                    'name' => $item['question'],
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $item['answer'],
                    ],
                ])
                ->values()
                ->all(),
        ];
    }
}
