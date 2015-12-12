<?php

namespace App\Providers;

use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        view()->share('appName', env('APP_NAME'));

        $this->app->bind('TwitterClient', function () {
            return new TwitterOAuth(env('TWITTER_ID'), env('TWITTER_SECRET'));
        });
    }
}
