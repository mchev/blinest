<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthorizationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::define('sendSuggestion', function () {
            return auth()->user()->isAdministrator() || auth()->user()->created_at < now()->subMonths(2) && floatval(auth()->user()->totalScores()->sum('score')) > 500;
        });
        Gate::define('changeRoomPicture', function () {
            return auth()->user()->isAdministrator() || auth()->user()->created_at < now()->subMonths(3) && floatval(auth()->user()->totalScores()->sum('score')) > 2000;
        });
        Gate::define('changeTeamPicture', function () {
            return auth()->user()->isAdministrator() || auth()->user()->created_at < now()->subMonths(3) && floatval(auth()->user()->totalScores()->sum('score')) > 2000;
        });
    }
}
