<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessUserCreated;
use App\Models\User;
use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirect($provider)
    {
        try {
            $driver = Socialite::driver($provider);

            // For Facebook, explicitly request required scopes
            if ($provider === 'facebook') {
                $driver->scopes(['email', 'public_profile']);
            }

            return $driver->redirect();
        } catch (\Exception $e) {
            Log::error("Social auth redirect failed for provider: {$provider}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('login')
                ->with('error', __('Authentication service temporarily unavailable. Please try again later.'));
        }
    }

    public function callback($provider)
    {
        try {
            $getInfo = Socialite::driver($provider)->user();

            // Log successful authentication for monitoring
            Log::info('Social auth success', [
                'provider' => $provider,
                'provider_id' => $getInfo->id,
                'email' => $getInfo->email ?? 'not provided',
                'ip' => request()->ip(),
            ]);

            $user = $this->createUser($getInfo, $provider);
            auth()->login($user);

            return redirect()->intended(AppServiceProvider::HOME);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $body = $response ? json_decode($response->getBody()->getContents(), true) : null;

            // Enhanced logging for Facebook errors
            Log::error('Social auth callback HTTP error', [
                'provider' => $provider,
                'error' => $e->getMessage(),
                'http_status' => $response?->getStatusCode(),
                'facebook_error' => $body,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'url' => request()->fullUrl(),
            ]);

            $errorMessage = __('Failed to authenticate with :provider. Please try again.', ['provider' => ucfirst($provider)]);

            // Provide specific error messages for Facebook
            if ($provider === 'facebook' && $body) {
                $fbError = $body['error'] ?? null;
                if ($fbError) {
                    $errorCode = $fbError['code'] ?? null;
                    $errorMessage = $fbError['message'] ?? $errorMessage;

                    // Common Facebook error codes
                    if ($errorCode === 190) {
                        $errorMessage = __('Facebook authentication token is invalid or expired.');
                    } elseif ($errorCode === 200) {
                        $errorMessage = __('Facebook app is not available. Please contact support.');
                    }

                    Log::warning('Facebook OAuth error', [
                        'code' => $errorCode,
                        'message' => $errorMessage,
                        'type' => $fbError['type'] ?? null,
                    ]);
                }
            }

            return redirect()->route('login')
                ->with('error', $errorMessage);
        } catch (\Exception $e) {
            Log::error('Social auth callback failed', [
                'provider' => $provider,
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'class' => get_class($e),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'url' => request()->fullUrl(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('login')
                ->with('error', __('Failed to authenticate with :provider. Please try again.', ['provider' => ucfirst($provider)]));
        }
    }

    public function createUser($getInfo, $provider)
    {
        $user = User::where('provider_id', $getInfo->id)->first();
        $email = User::where('email', $getInfo->email)->first();

        if (! $user && ! $email) {
            if (User::whereName($getInfo->name)->exists()) {
                $name = $this->incrementUsername($getInfo->name);
            } else {
                $name = $getInfo->name;
            }

            $user = User::create([
                'name' => $name,
                'email' => $getInfo->email,
                'provider' => $provider,
                'provider_id' => $getInfo->id,
            ]);

            ProcessUserCreated::dispatch($user);
        }

        if (! $user && $email) {
            $email->provider = $provider;
            $email->provider_id = $getInfo->id;
            $email->update();
            $user = $email;
        }

        return $user;
    }

    public function incrementUsername($username)
    {
        $original = $username;
        $count = 0;

        while (User::whereName($username)->exists()) {
            $username = "{$original}-".$count++;
        }

        return $username;
    }
}
