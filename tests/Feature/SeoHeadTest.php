<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\FAQ;
use App\Models\Page;
use App\Models\Room;
use App\Models\User;
use App\Seo\RoomHead;
use App\Services\RoomPresenceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Head\Facades\Head;
use Tests\TestCase;

class SeoHeadTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_server_side_title_and_description(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertOk();
        $response->assertSee('data-inertia="title"', false);
        $response->assertSee('quiz musicaux', false);
        $response->assertSee('name="description"', false);
    }

    public function test_homepage_inertia_page_includes_head_prop_for_server_head(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertOk();

        $content = $response->getContent();

        $this->assertMatchesRegularExpression(
            '/<script data-page="[^"]+" type="application\/json">(.+?)<\/script>/s',
            $content,
        );

        preg_match('/<script data-page="[^"]+" type="application\/json">(.+?)<\/script>/s', $content, $matches);

        $page = json_decode($matches[1], true);

        $this->assertIsArray($page);
        $this->assertContains('head', $page['sharedProps'] ?? []);
        $this->assertArrayHasKey('head', $page['props']);
        $this->assertNotEmpty($page['props']['head']);

        $titleElement = collect($page['props']['head'])
            ->first(fn (string $element): bool => str_contains($element, '<title'));

        $this->assertIsString($titleElement);
        $this->assertStringContainsString('data-inertia="title"', $titleElement);
        $this->assertStringContainsString('quiz', strtolower(strip_tags($titleElement)));
    }

    public function test_homepage_title_is_translated_when_locale_is_french(): void
    {
        $response = $this->withSession(['locale' => 'fr'])->get(route('home'));

        $response->assertOk();
        $response->assertSee('Quiz musicaux multijoueurs gratuits | Blinest', false);
        $response->assertDontSee('Free multiplayer music quizzes | Blinest', false);
    }

    public function test_homepage_title_is_english_with_url_prefix(): void
    {
        $response = $this->get('/en');

        $response->assertOk();
        $response->assertSee('Free multiplayer music quizzes | Blinest', false);
    }

    public function test_homepage_search_is_hidden_from_robots(): void
    {
        $this->mock(RoomPresenceService::class, function ($mock): void {
            $mock->shouldReceive('getMemberCountsForRooms')
                ->andReturn([]);
        });

        $response = $this->get(route('home', ['search' => 'jazz']));

        $response->assertOk();
        $response->assertSee('name="robots"', false);
        $response->assertSee('content="none"', false);
    }

    public function test_lang_query_redirects_to_english_prefix(): void
    {
        $response = $this->get('/?lang=en');

        $response->assertRedirect('/en');
    }

    public function test_lang_query_on_french_url_redirects_without_query(): void
    {
        $response = $this->get('/docs/howto?lang=fr');

        $response->assertRedirect('/docs/howto');
    }

    public function test_language_route_redirects_to_prefixed_url_for_localizable_pages(): void
    {
        $response = $this->from('/docs/howto')->get(route('language', ['language' => 'en']));

        $response->assertRedirect('/en/docs/howto');
        $this->assertSame('en', session('locale'));
    }

    public function test_language_route_keeps_same_url_for_non_localizable_pages(): void
    {
        $response = $this->from('/login')->get(route('language', ['language' => 'en']));

        $response->assertRedirect('/login');
        $this->assertSame('en', session('locale'));
    }

    public function test_language_route_keeps_room_urls_without_locale_prefix(): void
    {
        $response = $this->from('/rooms/quiz-general')->get(route('language', ['language' => 'es']));

        $response->assertRedirect('/rooms/quiz-general');
        $this->assertSame('es', session('locale'));
    }

    public function test_sitemap_lists_canonical_room_urls_once(): void
    {
        $category = Category::create(['name' => 'Pop']);
        $owner = User::factory()->create();

        Room::create([
            'name' => 'Sitemap Room',
            'slug' => 'sitemap-room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $content = $response->getContent();
        $canonical = route('rooms.show', 'sitemap-room');

        $this->assertSame(1, substr_count($content, $canonical));
    }

    public function test_lang_query_on_room_url_strips_query_without_locale_prefix(): void
    {
        $category = Category::create(['name' => 'Pop']);
        $owner = User::factory()->create();

        Room::create([
            'name' => 'Quiz General',
            'slug' => 'quiz-general',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $response = $this->get('/rooms/quiz-general?lang=es');

        $response->assertRedirect('/rooms/quiz-general');
    }

    public function test_cms_page_includes_canonical_and_hreflang_alternates(): void
    {
        Page::create([
            'title' => 'Terms of Service',
            'slug' => 'terms',
            'content' => '<p>Terms content</p>',
            'revised_at' => now(),
        ]);

        $response = $this->get(route('pages.show', 'terms'));

        $response->assertOk();
        $response->assertSee('Terms of Service', false);
        $response->assertSee(route('pages.show', 'terms', absolute: true), false);
        $response->assertSee('hreflang="fr"', false);
        $response->assertSee('hreflang="en"', false);
        $response->assertSee('hreflang="es"', false);
        $response->assertSee('/en/pages/terms', false);
        $response->assertSee('/es/pages/terms', false);
    }

    public function test_room_head_sets_canonical_without_hreflang_alternates(): void
    {
        $category = Category::create(['name' => 'Pop']);
        $owner = User::factory()->create();

        $room = Room::create([
            'name' => 'SEO Room',
            'slug' => 'seo-room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $room->load('owner', 'category');

        app(RoomHead::class)->apply($room, roundsCount: 3);

        $html = Head::render()->toHtml();

        $this->assertStringContainsString(route('rooms.show', 'seo-room'), $html);
        $this->assertStringNotContainsString('hreflang=', $html);
        $this->assertStringNotContainsString('/en/rooms/seo-room', $html);
    }

    public function test_spanish_homepage_includes_hreflang_alternates(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/es');

        $response->assertOk();
        $response->assertSee('hreflang="fr"', false);
        $response->assertSee('hreflang="en"', false);
        $response->assertSee('hreflang="es"', false);
        $response->assertSee('/es', false);
    }

    public function test_googlebot_always_gets_french_homepage_title(): void
    {
        $response = $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
            'Accept-Language' => 'en-US,en;q=0.9',
        ])->get(route('home'));

        $response->assertOk();
        $response->assertSee('Quiz musicaux multijoueurs gratuits | Blinest', false);
        $response->assertHeader('Content-Language', 'fr');
    }

    public function test_homepage_includes_hreflang_alternates(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('hreflang="fr"', false);
        $response->assertSee('hreflang="en"', false);
        $response->assertSee('hreflang="es"', false);
        $response->assertSee('hreflang="x-default"', false);
        $response->assertSee('/en', false);
        $response->assertSee('/es', false);
        $response->assertDontSee('lang=en', false);
    }

    public function test_contact_page_renders_server_side_meta(): void
    {
        $response = $this->withSession(['locale' => 'fr'])->get(route('contact'));

        $response->assertOk();
        $response->assertSee('Contact', false);
        $response->assertSee('Contactez l&#039;équipe Blinest', false);
    }

    public function test_faq_redirects_to_docs_faq(): void
    {
        $response = $this->get('/faq');

        $response->assertRedirect(route('docs.faq'));
    }

    public function test_docs_howto_renders_structured_data(): void
    {
        $response = $this->get(route('docs.howto'));

        $response->assertOk();
        $response->assertSee('application/ld+json', false);
        $response->assertSee('HowTo', false);
        $response->assertSee('Comment jouer', false);
    }

    public function test_docs_howto_title_is_translated_in_french(): void
    {
        $response = $this->withSession(['locale' => 'fr'])->get(route('docs.howto'));

        $response->assertOk();
        $response->assertSee('Comment jouer à un blind test sur Blinest', false);
    }

    public function test_faq_page_renders_faq_schema_when_questions_exist(): void
    {
        FAQ::create([
            'locale' => 'fr',
            'question' => 'Comment créer une room ?',
            'answer' => '<p>Depuis votre tableau de bord.</p>',
        ]);

        $response = $this->get(route('docs.faq'));

        $response->assertOk();
        $response->assertSee('FAQPage', false);
        $response->assertSee('Comment créer une room ?', false);
    }

    public function test_room_head_sets_title_description_and_schema(): void
    {
        $category = Category::create(['name' => 'Pop']);
        $owner = User::factory()->create();

        $room = Room::create([
            'name' => 'SEO Test Room',
            'slug' => 'seo-test-room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $room->load('owner', 'category');

        app(RoomHead::class)->apply($room, roundsCount: 3);

        $html = Head::render();

        $this->assertStringContainsString('SEO Test Room', $html);
        $this->assertStringContainsString('VideoGame', $html);
        $this->assertStringContainsString('application/ld+json', $html);
        $this->assertStringNotContainsString('AggregateRating', $html);
    }

    public function test_admin_pages_are_noindex(): void
    {
        $admin = User::factory()->create(['is_administrator' => true]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('noindex', false);
    }

    public function test_auth_login_page_is_noindex(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertSee('noindex', false);
    }

    public function test_robots_txt_references_sitemap_and_llms_txt(): void
    {
        $response = $this->get(route('robots'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $response->assertSee('Sitemap:', false);
        $response->assertSee('/sitemap.xml', false);
        $response->assertSee('/llms.txt', false);
        $response->assertSee('GPTBot', false);
    }

    public function test_llms_txt_is_available(): void
    {
        $response = $this->get(route('llms'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $response->assertSee('# Blinest', false);
        $response->assertSee('multiplayer music quiz', false);
        $response->assertSee('/docs/howto', false);
    }

    public function test_no_vue_head_components_remain_in_pages(): void
    {
        $files = glob(base_path('resources/js/Pages/**/*.vue'));

        foreach ($files as $file) {
            $contents = file_get_contents($file);

            $this->assertDoesNotMatchRegularExpression(
                '/<Head[\s>]/',
                $contents,
                "Vue Head component still present in {$file}"
            );

            $this->assertDoesNotMatchRegularExpression(
                '/StructuredData\.vue/',
                $contents,
                "StructuredData component still imported in {$file}"
            );
        }
    }
}
