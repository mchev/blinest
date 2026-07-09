<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdsTxtRedirectTest extends TestCase
{
    public function test_ads_txt_is_not_served_from_public_directory(): void
    {
        $this->assertFileDoesNotExist(public_path('ads.txt'));
    }
}
