<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class EloquentObserverServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        \App\Domain\User\Entities\User::observe(
            \App\Domain\User\Observers\UserObserver::class
        );
        \App\Domain\News\Entities\News::observe(
            \App\Domain\News\Observers\NewsObserver::class
        );
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
