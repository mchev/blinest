<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdsTxtRedirectTest extends TestCase
{
    public function test_ads_txt_includes_google_adsense_publisher_entry(): void
    {
        $path = public_path('ads.txt');

        $this->assertFileExists($path);
        $this->assertStringContainsString(
            'google.com, pub-6495635642797272, DIRECT, f08c47fec0942fa0',
            file_get_contents($path)
        );
    }
}
