<?php

namespace App\Services;

use App\Contracts\Services\CrawlerPageServiceContract;
use App\Contracts\Services\CrawlerTaskServiceContract;
use App\Models\CrawlerTask;

class CrawlerTaskService implements CrawlerTaskServiceContract
{
    public function __construct(
        protected CrawlerPageServiceContract $crawlerPageService,
    ) {}

    public function create(string $url): CrawlerTask
    {
        $crawlerTask = new CrawlerTask([
            'url' => $url,
        ]);

        $crawlerTask->save();

        // enqueue entrypoint URL as a new crawler page
        $this->crawlerPageService->createCrawlerPage($crawlerTask, $url);

        return $crawlerTask;
    }

    public function initiateFinish(CrawlerTask $crawlerTask): bool
    {
        return $crawlerTask->initiateFinish();
    }
}
