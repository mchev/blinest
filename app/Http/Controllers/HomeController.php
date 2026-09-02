<?php

namespace App\Http\Controllers;

use App\Seo\HomeHead;
use App\Services\Donations\DonationGoalService;
use App\Services\HomeCatalogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __construct(
        private HomeHead $homeHead,
        private HomeCatalogService $homeCatalog,
        private DonationGoalService $donationGoal,
    ) {}

    public function index(Request $request)
    {
        if ($request->filled('search')) {
            $this->homeHead->applyForSearch();

            return Inertia::render('Home/Index', [
                'filters' => fn () => $request->all('search'),
                'search_result' => fn () => $this->homeCatalog->searchRooms($request),
            ]);
        }

        $this->homeHead->apply();

        return Inertia::render('Home/Index', [
            'filters' => fn () => $request->all('search'),
            'catalog' => fn () => $this->homeCatalog->resolveTab($request),
            'catalog_category_ids' => fn () => $this->homeCatalog->resolveCategoryIds($request),
            'catalog_category_id' => fn () => ($ids = $this->homeCatalog->resolveCategoryIds($request)) !== [] ? $ids[0] : null,
            'catalog_items' => Inertia::scroll(fn () => $this->homeCatalog->paginate($request)),
            'weekly_top_users' => fn () => $this->donationGoal->annotateSupporterStatus(
                Cache::get('weekly-top-10-users', []) ?? [],
            ),
            'featured_rooms' => fn () => $this->homeCatalog->featuredRooms(),
            'public_categories' => fn () => $this->homeCatalog->publicCategories(),
            'community_categories' => fn () => $this->homeCatalog->communityCategories(),
            'catalog_tab_player_counts' => fn () => $this->homeCatalog->catalogTabPlayerCounts(),
            'homepage_hidden_category_ids' => fn () => config('blinest.homepage_hidden_category_ids', []),
        ]);
    }
}
