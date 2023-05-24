<?php

namespace App\Providers;

use App\Contracts\Services\CrawlerFetchServiceContract;
use App\Contracts\Services\CrawlerPageServiceContract;
use App\Contracts\Services\CrawlerParseServiceContract;
use App\Contracts\Services\CrawlerTaskServiceContract;
use App\Services\CrawlerFetchService;
use App\Services\CrawlerPageService;
use App\Services\CrawlerParseService;
use App\Services\CrawlerTaskService;
use Illuminate\Support\ServiceProvider;

class CrawlerServiceProvider extends ServiceProvider
{
    public $bindings = [
        CrawlerTaskServiceContract::class => CrawlerTaskService::class,
        CrawlerPageServiceContract::class => CrawlerPageService::class,
        CrawlerFetchServiceContract::class => CrawlerFetchService::class,
        CrawlerParseServiceContract::class => CrawlerParseService::class,
    ];

    public function register(): void
    {

    }

    public function boot(): void
    {

    }
}
