<?php

namespace App\Seo;

class BreadcrumbSchema
{
    /**
     * @param  list<array{label: string, href: string|null}>  $breadcrumbs
     * @return array<string, mixed>
     */
    public static function build(array $breadcrumbs): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect($breadcrumbs)
                ->values()
                ->map(fn (array $crumb, int $index): array => [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'name' => $crumb['label'],
                    ...($crumb['href'] !== null ? ['item' => $crumb['href']] : []),
                ])
                ->all(),
        ];
    }
}
