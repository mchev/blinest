<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //
    ];

    /**
     * Register any application authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('changeRoomPicture', function (User $user) {
            return ($user->created_at < now()->subMonths(3) && $user->totalScores()->sum('score') > 2000) ? true : false;
        });
        Gate::define('changeTeamPicture', function (User $user) {
            return ($user->created_at < now()->subMonths(3) && $user->totalScores()->sum('score') > 2000) ? true : false;
        });
    }
}
