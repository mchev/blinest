<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserPhotoUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
    }

    public function test_user_can_upload_avatar(): void
    {
        $user = User::factory()->create(['is_guest' => false]);

        $this->actingAs($user)
            ->from(route('me'))
            ->post(route('users.photo.update', $user), [
                'photo' => UploadedFile::fake()->image('avatar.jpg', 400, 400),
            ])
            ->assertRedirect(route('me'))
            ->assertSessionHas('success');

        $user->refresh();

        $this->assertNotNull($user->photo_path);
    }

    public function test_user_can_remove_custom_avatar(): void
    {
        $user = User::factory()->create(['is_guest' => false]);

        $this->actingAs($user)
            ->post(route('users.photo.update', $user), [
                'photo' => UploadedFile::fake()->image('avatar.jpg'),
            ]);

        $user->refresh();
        $this->assertNotNull($user->photo_path);

        $this->actingAs($user)
            ->from(route('me'))
            ->delete(route('users.photo.destroy', $user))
            ->assertRedirect(route('me'))
            ->assertSessionHas('success');

        $user->refresh();

        $this->assertNull($user->photo_path);
    }

    public function test_user_cannot_update_another_users_avatar(): void
    {
        $user = User::factory()->create(['is_guest' => false]);
        $other = User::factory()->create(['is_guest' => false]);

        $this->actingAs($user)
            ->post(route('users.photo.update', $other), [
                'photo' => UploadedFile::fake()->image('avatar.jpg'),
            ])
            ->assertForbidden();
    }
}
