<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class GuestPlayerTest extends TestCase
{
    use RefreshDatabase;

    private function createRoom(): Room
    {
        $category = Category::create(['name' => 'Test']);
        $owner = User::factory()->create();

        return tap(Room::create([
            'name' => 'Test Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_featured' => false,
            'is_public' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]), function (Room $room) {
            $room->refresh();
        });
    }

    public function test_guest_user_can_be_created(): void
    {
        $guest = User::create([
            'name' => 'Guest-12345',
            'email' => 'guest-abc123@b.est',
            'password' => null,
            'is_guest' => true,
            'guest_token' => 'test-token-uuid',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $guest->id,
            'is_guest' => true,
            'guest_token' => 'test-token-uuid',
        ]);

        $this->assertTrue($guest->isGuest());
    }

    public function test_non_guest_user_is_not_guest(): void
    {
        $user = User::factory()->create();

        $this->assertFalse($user->isGuest());
    }

    public function test_room_has_slug_after_creation(): void
    {
        $room = $this->createRoom();
        $this->assertNotNull($room->slug);
        $this->assertEquals('test-room', $room->slug);
    }

    public function test_guest_join_route_exists_and_works(): void
    {
        $room = $this->createRoom();

        $response = $this->post('/rooms/'.$room->slug.'/guest-join');

        $response->assertRedirect();
        $this->assertEquals(1, User::where('is_guest', true)->count());
    }

    public function test_guest_is_authenticated_after_join(): void
    {
        $room = $this->createRoom();

        $response = $this->post('/rooms/'.$room->slug.'/guest-join');

        $response->assertRedirect();

        $this->assertAuthenticated();
        $this->assertTrue(Auth::user()->isGuest());
    }

    public function test_guest_has_no_guest_token_cookie(): void
    {
        $room = $this->createRoom();

        $response = $this->post('/rooms/'.$room->slug.'/guest-join');

        $response->assertRedirect();
        $cookies = $response->headers->getCookies();
        $guestCookie = collect($cookies)->first(fn ($cookie) => $cookie->getName() === 'guest_token');

        $this->assertNull($guestCookie, 'guest_token cookie should NOT be set');
    }

    public function test_guest_rejoin_does_not_create_new_user(): void
    {
        $room = $this->createRoom();

        $this->post('/rooms/'.$room->slug.'/guest-join');
        $this->post('/rooms/'.$room->slug.'/guest-join');

        $this->assertEquals(1, User::where('is_guest', true)->count());
    }

    public function test_guest_cannot_access_me_page(): void
    {
        $room = $this->createRoom();
        $this->post('/rooms/'.$room->slug.'/guest-join');

        $response = $this->get(route('me'));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_guest_cannot_send_chat_message(): void
    {
        $room = $this->createRoom();
        $this->post('/rooms/'.$room->slug.'/guest-join');

        $response = $this->post(route('rooms.message.store', $room), [
            'body' => 'Hello from guest',
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_guest_profile_returns_404(): void
    {
        $guest = User::create([
            'name' => 'Guest-12345',
            'email' => 'guest-profile@b.est',
            'password' => null,
            'is_guest' => true,
            'guest_token' => 'profile-test',
        ]);

        $this->actingAs($guest);

        $response = $this->get(route('user.profile', $guest));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_scope_real_users_excludes_guests(): void
    {
        User::factory()->create(['name' => 'Alice']);
        User::factory()->create(['name' => 'Bob']);

        User::create([
            'name' => 'Guest-11111',
            'email' => 'guest-scope@b.est',
            'password' => null,
            'is_guest' => true,
            'guest_token' => 'scope-token',
        ]);

        $realUsers = User::realUsers()->get();

        $this->assertCount(2, $realUsers);
        $this->assertFalse($realUsers->contains(fn ($u) => $u->isGuest()));
    }
}
