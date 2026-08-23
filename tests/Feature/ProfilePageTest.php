<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Donation;
use App\Models\Room;
use App\Models\TotalScore;
use App\Models\User;
use App\Models\UserLevel;
use App\Services\Donations\DonationGoalService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ProfilePageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('donations.monthly_goal_cents', 10_000);
        Config::set('donations.timezone', 'Europe/Paris');
    }

    public function test_guest_profile_returns_not_found(): void
    {
        $guest = User::factory()->create(['is_guest' => true]);

        $this->actingAs($guest)
            ->get(route('user.profile', $guest))
            ->assertNotFound();
    }

    public function test_profile_renders_header_and_default_scores_tab(): void
    {
        $viewer = User::factory()->create(['is_guest' => false]);
        $profileUser = $this->createProfileUserWithScore();

        $this->actingAs($viewer)
            ->get(route('user.profile', $profileUser))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Profiles/Show')
                ->where('activeTab', 'scores')
                ->has('profile', fn (Assert $profile) => $profile
                    ->where('id', $profileUser->id)
                    ->where('name', $profileUser->name)
                    ->has('stats')
                    ->missing('donation_summary')
                    ->etc()
                )
                ->has('scores.data', 1)
                ->where('likes', null)
                ->where('bookmarks', null)
                ->where('minigames', null)
                ->missing('profileHighlights')
            );
    }

    public function test_profile_loads_highlights_via_deferred_props(): void
    {
        $viewer = User::factory()->create(['is_guest' => false]);
        $profileUser = $this->createProfileUserWithScore();

        Cache::flush();

        $this->actingAs($viewer)
            ->get(route('user.profile', $profileUser))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->missing('profileHighlights')
                ->loadDeferredProps(fn (Assert $reload) => $reload
                    ->has('profileHighlights.performance')
                    ->has('profileHighlights.rank')
                    ->has('profileHighlights.top_rooms')
                    ->has('profileHighlights.badges')
                )
            );
    }

    public function test_profile_minigames_tab_only_loads_minigames(): void
    {
        $viewer = User::factory()->create(['is_guest' => false]);
        $profileUser = User::factory()->create(['is_guest' => false]);

        $this->actingAs($viewer)
            ->get(route('user.profile', ['user' => $profileUser, 'tab' => 'minigames']))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('activeTab', 'minigames')
                ->where('scores', null)
                ->where('likes', null)
                ->where('bookmarks', null)
                ->has('minigames.games', 5)
                ->has('minigames.history')
            );
    }

    public function test_profile_likes_tab_only_loads_likes(): void
    {
        $viewer = User::factory()->create(['is_guest' => false]);
        $profileUser = User::factory()->create(['is_guest' => false]);

        $this->actingAs($viewer)
            ->get(route('user.profile', ['user' => $profileUser, 'tab' => 'likes']))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('activeTab', 'likes')
                ->where('scores', null)
                ->has('likes')
                ->where('bookmarks', null)
            );
    }

    public function test_profile_defers_donations_when_user_has_donations(): void
    {
        $viewer = User::factory()->create(['is_guest' => false]);
        $profileUser = User::factory()->create(['is_guest' => false]);

        Donation::query()->create([
            'stripe_checkout_session_id' => 'cs_profile_defer',
            'amount_cents' => 500,
            'currency' => 'eur',
            'month_key' => app(DonationGoalService::class)->monthKey(),
            'user_id' => $profileUser->id,
            'donated_at' => now('Europe/Paris'),
        ]);

        Cache::flush();

        $this->actingAs($viewer)
            ->get(route('user.profile', $profileUser))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('profile.donation_summary.donation_count', 1)
                ->missing('donations')
                ->loadDeferredProps(fn (Assert $reload) => $reload
                    ->has('donations', 1)
                )
            );
    }

    public function test_profile_hides_donation_history_when_user_opted_out(): void
    {
        $viewer = User::factory()->create(['is_guest' => false]);
        $profileUser = User::factory()->create([
            'is_guest' => false,
            'show_donation_history_on_profile' => false,
        ]);

        Donation::query()->create([
            'stripe_checkout_session_id' => 'cs_profile_hidden',
            'amount_cents' => 500,
            'currency' => 'eur',
            'month_key' => app(DonationGoalService::class)->monthKey(),
            'user_id' => $profileUser->id,
            'donated_at' => now('Europe/Paris'),
        ]);

        Cache::flush();

        $this->actingAs($viewer)
            ->get(route('user.profile', $profileUser))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->missing('profile.donation_summary')
                ->missing('donations')
            );
    }

    public function test_profile_defers_score_evolution(): void
    {
        $viewer = User::factory()->create(['is_guest' => false]);
        $profileUser = $this->createProfileUserWithScore();

        $this->actingAs($viewer)
            ->get(route('user.profile', $profileUser))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->missing('scoreEvolution')
                ->loadDeferredProps(fn (Assert $reload) => $reload
                    ->has('scoreEvolution')
                )
            );
    }

    public function test_profile_scores_tab_supports_server_sort(): void
    {
        $viewer = User::factory()->create(['is_guest' => false]);
        $profileUser = $this->createProfileUserWithScore();

        $this->actingAs($viewer)
            ->get(route('user.profile', ['user' => $profileUser, 'tab' => 'scores', 'sort' => 'score', 'direction' => 'desc']))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('scoresSort', 'score')
                ->where('scoresDirection', 'desc')
                ->has('scores.data', 1)
            );
    }

    private function createProfileUserWithScore(): User
    {
        $category = Category::create(['name' => 'Profile Category']);
        $user = User::factory()->create(['is_guest' => false, 'elo' => 1500]);

        UserLevel::create([
            'user_id' => $user->id,
            'level' => 3,
            'total_xp' => 300,
            'current_xp' => 30,
            'xp_for_next_level' => 100,
            'score_public_rooms' => 0,
        ]);

        $room = Room::create([
            'name' => 'Profile Room',
            'slug' => 'profile-room',
            'user_id' => $user->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        TotalScore::create([
            'totalscorable_type' => User::class,
            'totalscorable_id' => $user->id,
            'room_id' => $room->id,
            'score' => 42,
        ]);

        return $user;
    }
}
