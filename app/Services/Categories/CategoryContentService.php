<?php

namespace App\Services\Categories;

use App\Models\Category;

class CategoryContentService
{
    /**
     * @param  list<array<string, mixed>>  $rooms
     * @return array{
     *     heading: string,
     *     intro: string,
     *     intro_secondary: string,
     *     rooms_heading: string,
     *     faq: list<array{question: string, answer: string}>
     * }
     */
    public function forCategory(Category $category, array $rooms): array
    {
        $label = __($category->name);
        $roomNames = collect($rooms)->pluck('name')->join(', ');
        $replace = [
            'category' => $label,
            'count' => (string) count($rooms),
            'rooms' => $roomNames,
        ];

        return [
            'heading' => $this->text($category->slug, 'heading', $replace, 'Category page heading'),
            'intro' => $this->text($category->slug, 'intro', $replace, 'Category page intro'),
            'intro_secondary' => $this->text($category->slug, 'intro_secondary', $replace, 'Category page intro secondary'),
            'rooms_heading' => $this->text($category->slug, 'rooms_heading', $replace, 'Category page rooms heading'),
            'faq' => [
                [
                    'question' => $this->text($category->slug, 'faq_q1', $replace, 'Category page FAQ q1'),
                    'answer' => $this->text($category->slug, 'faq_a1', $replace, 'Category page FAQ a1'),
                ],
                [
                    'question' => $this->text($category->slug, 'faq_q2', $replace, 'Category page FAQ q2'),
                    'answer' => $this->text($category->slug, 'faq_a2', $replace, 'Category page FAQ a2'),
                ],
                [
                    'question' => $this->text($category->slug, 'faq_q3', $replace, 'Category page FAQ q3'),
                    'answer' => $this->text($category->slug, 'faq_a3', $replace, 'Category page FAQ a3'),
                ],
            ],
        ];
    }

    public function metaTitle(Category $category): string
    {
        $replace = ['category' => __($category->name)];

        return $this->text($category->slug, 'meta_title', $replace, 'Category page title');
    }

    public function metaDescription(Category $category, int $roomsCount): string
    {
        $replace = [
            'category' => __($category->name),
            'count' => (string) $roomsCount,
        ];

        return $this->text($category->slug, 'meta_description', $replace, 'Category page meta description');
    }

    public function schemaName(Category $category): string
    {
        $replace = ['category' => __($category->name)];

        return $this->text($category->slug, 'schema_name', $replace, 'Category page schema name');
    }

    /**
     * @param  array<string, string>  $replace
     */
    private function text(string $slug, string $field, array $replace, string $fallbackKey): string
    {
        $specificKey = "Category slug {$slug} {$field}";
        $specific = __($specificKey, $replace);

        if ($specific !== $specificKey) {
            return $specific;
        }

        return __($fallbackKey, $replace);
    }
}
