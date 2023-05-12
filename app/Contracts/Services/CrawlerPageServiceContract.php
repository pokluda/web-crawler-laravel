<?php

namespace App\Contracts\Services;

use App\Models\CrawlerTask;
use App\Models\CrawlerPage;

interface CrawlerPageServiceContract
{
    public function createCrawlerPage(CrawlerTask $crawlerTask, string $url): CrawlerPage;
    public function processCrawlerPage(CrawlerPage $crawlerPage): void;
}
