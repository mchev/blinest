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
             $user = User::create([
                 'name'     => $getInfo->name,
                 'email'    => $getInfo->email,
                 'provider' => $provider,
                 'provider_id' => $getInfo->id,
             ]);
         }

         if (! $user && $email) {
             $email->provider = $provider;
             $email->provider_id = $getInfo->id;
             $email->update();
             $user = $email;
         }

         ProcessUserCreated::dispatch($user);

         return $user;
     }
 }
