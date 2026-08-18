<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Seo\CategoryHead;
use App\Seo\SeoLandingHtml;
use App\Services\Categories\CategoryContentService;
use App\Services\Categories\CategoryLandingService;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryLandingService $categoryLanding,
        private CategoryContentService $categoryContent,
        private CategoryHead $categoryHead,
        private SeoLandingHtml $seoLandingHtml,
    ) {}

    public function show(Category $category)
    {
        $rooms = $this->categoryLanding->officialRooms($category);

        if ($rooms === []) {
            abort(404);
        }

        $content = $this->categoryContent->forCategory($category, $rooms);

        $this->categoryHead->apply($category, $rooms, $this->categoryContent);

        $this->seoLandingHtml->shareCategory([
            'category' => $category,
            'content' => $content,
            'rooms' => $rooms,
        ]);

        return Inertia::render('Categories/Show', [
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
            ],
            'content' => $content,
            'rooms' => $rooms,
            'roomsCount' => count($rooms),
        ]);
    }
}
