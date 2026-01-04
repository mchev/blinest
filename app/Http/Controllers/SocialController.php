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
            return Socialite::driver($provider)->redirect();
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
            $user = $this->createUser($getInfo, $provider);
            auth()->login($user);

            return redirect()->intended(AppServiceProvider::HOME);
        } catch (\Exception $e) {
            Log::error("Social auth callback failed for provider: {$provider}", [
                'error' => $e->getMessage(),
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
