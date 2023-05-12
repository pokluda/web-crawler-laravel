<?php

namespace App\Contracts\Services;

use App\Models\CrawlerTask;

interface CrawlerTaskServiceContract
{
    public function create(string $url): CrawlerTask;
    public function initiateFinish(CrawlerTask $crawlerTask): bool;
}
