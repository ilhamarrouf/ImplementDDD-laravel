<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \App\Domain\News\Repositories\NewsRepository::class,
            \App\Domain\News\Repositories\NewsRepositoryEloquent::class
        );
        $this->app->bind(
            \App\Domain\Topic\Repositories\TopicRepository::class,
            \App\Domain\Topic\Repositories\TopicRepositoryEloquent::class
        );
        //:end-bindings:
    }
}
