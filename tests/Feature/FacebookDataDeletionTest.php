<?php

namespace Tests\Feature;

use App\Enums\FacebookDataDeletionAction;
use App\Models\FacebookDataDeletionRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class FacebookDataDeletionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('services.facebook.client_secret', 'test-facebook-secret');
    }

    public function test_callback_rejects_missing_signed_request(): void
    {
        $this->post(route('facebook.data-deletion.callback'))
            ->assertBadRequest()
            ->assertJson(['error' => 'Missing signed_request.']);
    }

    public function test_callback_rejects_invalid_signed_request(): void
    {
        $this->post(route('facebook.data-deletion.callback'), [
            'signed_request' => 'invalid.payload',
        ])
            ->assertBadRequest()
            ->assertJson(['error' => 'Invalid signed_request.']);
    }

    public function test_callback_deletes_facebook_only_account(): void
    {
        $user = User::factory()->create([
            'is_guest' => false,
            'password' => null,
            'provider' => 'facebook',
            'provider_id' => 'fb-user-123',
        ]);

        $response = $this->post(route('facebook.data-deletion.callback'), [
            'signed_request' => $this->signedRequest('fb-user-123'),
        ]);

        $response->assertOk()
            ->assertJsonStructure(['url', 'confirmation_code']);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);

        $this->assertDatabaseHas('facebook_data_deletion_requests', [
            'facebook_user_id' => 'fb-user-123',
            'action' => FacebookDataDeletionAction::Deleted->value,
        ]);
    }

    public function test_callback_unlinks_facebook_for_password_account(): void
    {
        $user = User::factory()->create([
            'is_guest' => false,
            'password' => Hash::make('secure-password'),
            'provider' => 'facebook',
            'provider_id' => 'fb-user-linked',
        ]);

        $this->post(route('facebook.data-deletion.callback'), [
            'signed_request' => $this->signedRequest('fb-user-linked'),
        ])->assertOk();

        $user->refresh();

        $this->assertNull($user->provider);
        $this->assertNull($user->provider_id);

        $this->assertDatabaseHas('facebook_data_deletion_requests', [
            'facebook_user_id' => 'fb-user-linked',
            'user_id' => $user->id,
            'action' => FacebookDataDeletionAction::Unlinked->value,
        ]);
    }

    public function test_callback_marks_unknown_facebook_ids_as_not_found(): void
    {
        $this->post(route('facebook.data-deletion.callback'), [
            'signed_request' => $this->signedRequest('fb-unknown-user'),
        ])->assertOk();

        $this->assertDatabaseHas('facebook_data_deletion_requests', [
            'facebook_user_id' => 'fb-unknown-user',
            'user_id' => null,
            'action' => FacebookDataDeletionAction::NotFound->value,
        ]);
    }

    public function test_status_page_renders_processed_request(): void
    {
        $request = FacebookDataDeletionRequest::query()->create([
            'confirmation_code' => 'ABC123STATUS',
            'facebook_user_id' => 'fb-status-user',
            'action' => FacebookDataDeletionAction::Unlinked,
            'source' => 'callback',
            'processed_at' => now(),
        ]);

        $this->get(route('facebook.data-deletion.status', $request->confirmation_code))
            ->assertOk()
            ->assertSee('ABC123STATUS')
            ->assertSee(__('Facebook data deletion status unlinked'));
    }

    public function test_status_endpoint_returns_json(): void
    {
        $request = FacebookDataDeletionRequest::query()->create([
            'confirmation_code' => 'JSON123STATUS',
            'facebook_user_id' => 'fb-json-user',
            'action' => FacebookDataDeletionAction::Deleted,
            'source' => 'callback',
            'processed_at' => now(),
        ]);

        $this->getJson(route('facebook.data-deletion.status', $request->confirmation_code))
            ->assertOk()
            ->assertJson([
                'confirmation_code' => 'JSON123STATUS',
                'status' => FacebookDataDeletionAction::Deleted->value,
            ]);
    }

    public function test_artisan_command_processes_ids_from_file(): void
    {
        $user = User::factory()->create([
            'is_guest' => false,
            'password' => null,
            'provider' => 'facebook',
            'provider_id' => 'fb-csv-user',
        ]);

        $path = tempnam(sys_get_temp_dir(), 'fb-deletion-');
        file_put_contents($path, "app-scoped-user-id\nfb-csv-user\n");

        $this->artisan('facebook:process-deletion-ids', ['file' => $path])
            ->assertSuccessful();

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseHas('facebook_data_deletion_requests', [
            'facebook_user_id' => 'fb-csv-user',
            'action' => FacebookDataDeletionAction::Deleted->value,
            'source' => 'manual',
        ]);

        @unlink($path);
    }

    public function test_artisan_command_supports_dry_run(): void
    {
        User::factory()->create([
            'is_guest' => false,
            'password' => null,
            'provider' => 'facebook',
            'provider_id' => 'fb-dry-run',
        ]);

        $path = tempnam(sys_get_temp_dir(), 'fb-deletion-dry-');
        file_put_contents($path, "fb-dry-run\n");

        $this->artisan('facebook:process-deletion-ids', [
            'file' => $path,
            '--dry-run' => true,
        ])->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'provider' => 'facebook',
            'provider_id' => 'fb-dry-run',
        ]);
        $this->assertDatabaseCount('facebook_data_deletion_requests', 0);

        @unlink($path);
    }

    private function signedRequest(string $userId, string $secret = 'test-facebook-secret'): string
    {
        $payload = rtrim(strtr(base64_encode(json_encode([
            'algorithm' => 'HMAC-SHA256',
            'expires' => time() + 3600,
            'issued_at' => time(),
            'user_id' => $userId,
        ], JSON_THROW_ON_ERROR)), '+/', '-_'), '=');

        $signature = hash_hmac('sha256', $payload, $secret, true);
        $encodedSignature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

        return $encodedSignature.'.'.$payload;
    }
}
