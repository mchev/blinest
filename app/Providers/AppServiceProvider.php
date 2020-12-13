<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        view()->composer('*', function($view) {
           $view->with('categories', \App\Game::where('public', 1)->orderBy('hit', 'DESC')->get());
        });

        Passport::personalAccessClientId(config('passport.personal_access_client.id'));
        Passport::personalAccessClientSecret(config('passport.personal_access_client.secret'));

        Schema::defaultStringLength(191);

    }
}
