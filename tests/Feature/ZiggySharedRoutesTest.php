<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ZiggySharedRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_page_shares_categories_show_in_ziggy_routes(): void
    {
        $response = $this->get(route('rankings.index'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->has('ziggy.routes')
            ->where('ziggy.routes', fn ($routes): bool => collect($routes)->has(['categories.show', 'rankings.index']))
        );
    }
}
