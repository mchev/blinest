<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Seo\CategoryHead;
use App\Services\Categories\CategoryContentService;
use App\Services\Categories\CategoryLandingService;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryLandingService $categoryLanding,
        private CategoryContentService $categoryContent,
        private CategoryHead $categoryHead,
    ) {}

    public function show(Category $category)
    {
        $rooms = $this->categoryLanding->officialRooms($category);

        if ($rooms === []) {
            abort(404);
        }

        $this->categoryHead->apply($category, $rooms, $this->categoryContent);

        return Inertia::render('Categories/Show', [
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
            ],
            'content' => $this->categoryContent->forCategory($category, $rooms),
            'rooms' => $rooms,
            'roomsCount' => count($rooms),
        ]);
    }
}
