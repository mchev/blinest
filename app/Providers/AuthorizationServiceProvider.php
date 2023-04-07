<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthorizationServiceProvider extends ServiceProvider
{

    public function boot() :void
    {

        Gate::define('changeRoomPicture', function () {
            return auth()->user()->created_at < now()->subMonths(3) && floatval(auth()->user()->totalScores()->sum('score')) > 2000;
        });
        Gate::define('changeTeamPicture', function () {
            return auth()->user()->created_at < now()->subMonths(3) && floatval(auth()->user()->totalScores()->sum('score')) > 2000;
        });
    }

}
