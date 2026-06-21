<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdsTxtRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_ads_txt_includes_google_adsense_publisher_entry(): void
    {
        $response = $this->get('/ads.txt');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $response->assertSee('google.com, pub-6495635642797272, DIRECT, f08c47fec0942fa0', false);
    }
}
