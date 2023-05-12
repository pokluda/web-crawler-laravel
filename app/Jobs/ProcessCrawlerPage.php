<?php

namespace App\Jobs;

use App\Models\CrawlerPage;
use App\Contracts\Services\CrawlerPageServiceContract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessCrawlerPage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public const QUEUE_NAME = 'jobs';

    public function __construct(
        public CrawlerPage $page,
    ) {}

    public function handle(CrawlerPageServiceContract $crawlerPageService): void
    {
        $crawlerPageService->processCrawlerPage($this->page);
    }
}
