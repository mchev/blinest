<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Room;
use App\Seo\LocaleUrl;
use App\Services\Categories\CategoryLandingService;

class SitemapController extends Controller
{
    public function __construct(private CategoryLandingService $categoryLanding) {}

    public function index()
    {
        return response()
            ->view('sitemap', [
                'locales' => LocaleUrl::availableLocales(),
                'publicRooms' => Room::isPublic()
                    ->whereNull('password')
                    ->whereNull('deleted_at')
                    ->withCount('rounds')
                    ->orderByDesc('rounds_count')
                    ->get(['slug', 'updated_at'])
                    ->map(fn (Room $room) => (object) [
                        'url' => route('rooms.show', $room->slug),
                        'updated_at' => $room->updated_at,
                    ]),
                'categories' => $this->categoryLanding->indexableCategories()
                    ->map(fn ($category) => (object) [
                        'urls' => collect(LocaleUrl::availableLocales())
                            ->map(fn (string $locale): string => LocaleUrl::localizedPath($category->landingPath(), $locale))
                            ->all(),
                        'updated_at' => $category->updated_at,
                    ]),
                'pages' => Page::select('id', 'title', 'slug', 'revised_at', 'updated_at')
                    ->orderByDesc('revised_at')
                    ->get()
                    ->unique('slug')
                    ->map(fn (Page $page) => (object) [
                        'urls' => collect(LocaleUrl::availableLocales())
                            ->map(fn (string $locale): string => LocaleUrl::localizedPath('pages/'.$page->slug, $locale))
                            ->all(),
                        'updated_at' => $page->revised_at ?? $page->updated_at,
                    ]),
            ])
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
