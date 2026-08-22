<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserPasswordUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_password_with_current_password(): void
    {
        $user = User::factory()->create([
            'is_guest' => false,
            'password' => Hash::make('old-password'),
        ]);

        $this->actingAs($user)
            ->from(route('me'))
            ->put(route('users.password.update', $user), [
                'current_password' => 'old-password',
                'password' => 'new-secure-password',
                'password_confirmation' => 'new-secure-password',
            ])
            ->assertRedirect(route('me'))
            ->assertSessionHas('success');

        $user->refresh();

        $this->assertTrue(Hash::check('new-secure-password', $user->password));
    }

    public function test_user_cannot_update_password_without_current_password(): void
    {
        $user = User::factory()->create([
            'is_guest' => false,
            'password' => Hash::make('old-password'),
        ]);

        $this->actingAs($user)
            ->from(route('me'))
            ->put(route('users.password.update', $user), [
                'password' => 'new-secure-password',
                'password_confirmation' => 'new-secure-password',
            ])
            ->assertSessionHasErrors('current_password');
    }

    public function test_social_user_can_set_password_without_current_password(): void
    {
        $user = User::factory()->create([
            'is_guest' => false,
            'password' => null,
            'provider' => 'google',
            'provider_id' => '12345',
        ]);

        $this->actingAs($user)
            ->from(route('me'))
            ->put(route('users.password.update', $user), [
                'password' => 'new-secure-password',
                'password_confirmation' => 'new-secure-password',
            ])
            ->assertRedirect(route('me'))
            ->assertSessionHas('success');

        $user->refresh();

        $this->assertTrue(Hash::check('new-secure-password', $user->password));
    }

    public function test_user_cannot_update_another_users_password(): void
    {
        $user = User::factory()->create(['is_guest' => false]);
        $other = User::factory()->create(['is_guest' => false]);

        $this->actingAs($user)
            ->put(route('users.password.update', $other), [
                'current_password' => 'password',
                'password' => 'new-secure-password',
                'password_confirmation' => 'new-secure-password',
            ])
            ->assertForbidden();
    }
}
