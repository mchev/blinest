<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Pagination\Paginator;
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

        //The paginator now uses the Tailwind CSS framework for its default styling. In order to keep using Bootstrap, you should add the following method 
        Paginator::useBootstrap();

        
/*
        Passport::personalAccessClientId(env('PASSPORT_PERSONAL_ACCESS_CLIENT_ID'));
        Passport::personalAccessClientSecret(env('PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET'));
*/
        Schema::defaultStringLength(191);

    }
}
