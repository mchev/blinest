<?php

namespace App\Providers;

use App\Models\User;
use App\Services\Donations\DonationGoalService;
use App\Services\Donations\DonorPerkService;
use App\Support\ClientIp;
use Carbon\Carbon;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Laravel\Horizon\Console\ClearCommand;
use Laravel\Horizon\Console\TerminateCommand;
use SocialiteProviders\Discord\Provider;
use SocialiteProviders\Manager\SocialiteWasCalled;

// use Illuminate\Support\Facades\Queue;
// use Illuminate\Queue\Events\JobFailed;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Register any application services.
     */
    public function register(): void
    {
        Model::unguard();

        $this->commands([
            ClearCommand::class,
            TerminateCommand::class,
        ]);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Queue::failing(function (JobFailed $event) {
        //     // $event->connectionName
        //     // $event->job
        //     // $event->exception
        // });

        Carbon::setLocale(config('app.locale'));

        Event::listen(function (SocialiteWasCalled $event) {
            $event->extendSocialite('discord', Provider::class);
            $event->extendSocialite('deezer', \SocialiteProviders\Deezer\Provider::class);
            $event->extendSocialite('google', \SocialiteProviders\Google\Provider::class);
            $event->extendSocialite('facebook', \SocialiteProviders\Facebook\Provider::class);
        });

        $this->bootAuth();
        $this->bootRoute();
        $this->bootViews();
    }

    public function bootAuth(): void
    {

        Gate::define('viewPulse', function (User $user) {
            return $user->isAdministrator();
        });

    }

    public function bootRoute(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: ClientIp::from($request));
        });

    }

    public function bootViews(): void
    {
        View::composer('app', function ($view): void {
            $donationGoal = app(DonationGoalService::class);
            $donorPerks = app(DonorPerkService::class);

            $serveEzoicAds = ! $donationGoal->shouldDisableAds()
                && ! $donorPerks->shouldDisableAdsForUser(auth()->user());

            $view->with('serveEzoicAds', $serveEzoicAds);
        });
    }
}
