<?php

namespace Tests\Unit;

use App\Seo\BreadcrumbSchema;
use App\Seo\FaqPageSchema;
use Tests\TestCase;

class SeoSchemaTest extends TestCase
{
    public function test_faq_page_schema_maps_questions_and_answers(): void
    {
        $schema = FaqPageSchema::build([
            ['question' => 'Q1', 'answer' => 'A1'],
            ['question' => 'Q2', 'answer' => 'A2'],
        ]);

        $this->assertSame('FAQPage', $schema['@type']);
        $this->assertCount(2, $schema['mainEntity']);
        $this->assertSame('Q1', $schema['mainEntity'][0]['name']);
        $this->assertSame('A1', $schema['mainEntity'][0]['acceptedAnswer']['text']);
    }

    public function test_breadcrumb_schema_maps_positions(): void
    {
        $schema = BreadcrumbSchema::build([
            ['label' => 'Home', 'href' => 'https://blinest.com'],
            ['label' => 'Rap FR', 'href' => null],
        ]);

        $this->assertSame('BreadcrumbList', $schema['@type']);
        $this->assertSame(1, $schema['itemListElement'][0]['position']);
        $this->assertSame('https://blinest.com', $schema['itemListElement'][0]['item']);
        $this->assertArrayNotHasKey('item', $schema['itemListElement'][1]);
    }
}
