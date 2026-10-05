<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdsTxtRedirectTest extends TestCase
{
    public function test_ads_txt_is_published_for_google_adsense(): void
    {
        $path = public_path('ads.txt');

        $this->assertFileExists($path);

        $contents = file_get_contents($path);

        $this->assertIsString($contents);
        $this->assertStringContainsString('google.com, pub-6495635642797272, DIRECT', $contents);
    }
}
