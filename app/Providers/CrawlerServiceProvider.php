<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CrawlerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            'App\Contracts\Services\CrawlerTaskServiceContract',
            'App\Services\CrawlerTaskService'
        );

        $this->app->bind(
            'App\Contracts\Services\CrawlerPageServiceContract',
            'App\Services\CrawlerPageService'
        );

        $this->app->bind(
            'App\Contracts\Services\CrawlerFetchServiceContract',
            'App\Services\CrawlerFetchService'
        );

        $this->app->bind(
            'App\Contracts\Services\CrawlerParseServiceContract',
            'App\Services\CrawlerParseService'
        );
    }

    public function boot(): void
    {
        //
    }
}
