<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessUserCreated;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        $getInfo = Socialite::driver($provider)->user();
        $user = $this->createUser($getInfo, $provider);
        auth()->login($user);

        return redirect()->intended(RouteServiceProvider::HOME);
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
