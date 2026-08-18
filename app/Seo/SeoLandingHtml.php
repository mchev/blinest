<?php

namespace App\Seo;

use Illuminate\Support\Facades\View;

class SeoLandingHtml
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function shareRoom(array $data): void
    {
        View::share('seoLandingHtml', view('seo.room', $data)->render());
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function shareCategory(array $data): void
    {
        View::share('seoLandingHtml', view('seo.category', $data)->render());
    }
}
