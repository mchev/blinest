<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\Request;

// use Illuminate\Support\Facades\Queue;
// use Illuminate\Queue\Events\JobFailed;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Model::unguard();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        // Fix to get real client IP address through Cloudflare
        Request::setTrustedProxies(
            ['REMOTE_ADDR'], 
            Request::HEADER_X_FORWARDED_FOR
        );

        // Queue::failing(function (JobFailed $event) {
        //     // $event->connectionName
        //     // $event->job
        //     // $event->exception
        // });
    }
}
