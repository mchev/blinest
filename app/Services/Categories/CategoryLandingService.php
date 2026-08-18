<?php

namespace App\Services\Categories;

use App\Models\Category;
use App\Services\HomeCatalogService;
use Illuminate\Support\Collection;

class CategoryLandingService
{
    public function __construct(private HomeCatalogService $homeCatalog) {}

    /**
     * @return list<array<string, mixed>>
     */
    public function officialRooms(Category $category): array
    {
        return $this->homeCatalog->officialRoomsForCategory($category->id);
    }

    public function hasOfficialRooms(Category $category): bool
    {
        return $this->officialRooms($category) !== [];
    }

    /**
     * @return Collection<int, Category>
     */
    public function indexableCategories(): Collection
    {
        $hiddenCategoryIds = config('blinest.homepage_hidden_category_ids', []);

        return Category::query()
            ->whereNotIn('id', $hiddenCategoryIds)
            ->whereHas('rooms', function ($query): void {
                $query->whereNull('password')->isPublic();
            })
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'updated_at']);
    }
}
