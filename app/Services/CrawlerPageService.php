<?php

namespace App\Services;

use App\Contracts\Services\CrawlerFetchServiceContract;
use App\Contracts\Services\CrawlerPageServiceContract;
use App\Contracts\Services\CrawlerParseServiceContract;
use App\Enums\CrawlerPageStatusEnum;
use App\Enums\PageLinkTypeEnum;
use App\Events\CrawlerTaskUpdatedEvent;
use App\Events\CrawlerPageFinishedEvent;
use App\Http\Requests\CrawlerTaskRequest;
use App\Jobs\ProcessCrawlerPage;
use App\Models\CrawlerTask;
use App\Models\CrawlerPage;
use Illuminate\Support\Facades\Validator;

class CrawlerPageService implements CrawlerPageServiceContract
{
    public function __construct(
        protected CrawlerFetchServiceContract $crawlerFetchService,
        protected CrawlerParseServiceContract $crawlerParseService,
    ) {}

    public function createCrawlerPage(CrawlerTask $crawlerTask, string $url): CrawlerPage
    {
        $crawlerPage = new CrawlerPage([
            'crawler_task_id' => $crawlerTask->id,
            'url' => $url,
        ]);

        $crawlerPage->save();

        ProcessCrawlerPage::dispatch($crawlerPage)->onQueue(ProcessCrawlerPage::QUEUE_NAME);

        return $crawlerPage;
    }

    public function processCrawlerPage(CrawlerPage $crawlerPage): void
    {
        // don't process and delete if its crawler task is no longer active
        if ($crawlerPage->crawlerTask->isNotActive()) {

            $crawlerPage->delete();

            // the crawler task should finish (if it hasn't yet)
            $crawlerPage->crawlerTask->finish();

            return;
        }

        $crawlerPage->status = CrawlerPageStatusEnum::PROCESSING;
        $crawlerPage->save();

        try {
            // fetch page for a given URL
            $fetchedPage = $this->crawlerFetchService->fetchPage($crawlerPage->url);

            // set fetched page data
            $crawlerPage->http_status_code = $fetchedPage->statusCode;
            $crawlerPage->page_load_duration = $fetchedPage->duration;

            $crawlerPage->status = CrawlerPageStatusEnum::FETCHED;
            $crawlerPage->save();
        }
        catch (\Throwable $e)
        {
            $crawlerPage->status = CrawlerPageStatusEnum::ERROR;
            $crawlerPage->save();

            report($e);
            return;
        }

        // get page DOM
        $pageDom = $this->crawlerParseService->getPageDom($fetchedPage->content);

        $crawlerPage = $this->parsePageData($crawlerPage, $crawlerPage->crawlerTask, $pageDom);

        $crawlerPage->status = CrawlerPageStatusEnum::FINISHED;
        $crawlerPage->save();

        // broadcast page finished and task updated events
        event(new CrawlerPageFinishedEvent($crawlerPage));
        event(new CrawlerTaskUpdatedEvent($crawlerPage->crawlerTask));

        // enqueue valid internal links found on this page
        $this->enqueueValidUrls($crawlerPage, $crawlerPage->crawlerTask, $pageDom);

        // check if the crawler task should finish
        if ($crawlerPage->crawlerTask->isNotActive()) {
            $crawlerPage->crawlerTask->finish();
        }
    }

    protected function parsePageData(CrawlerPage $crawlerPage, CrawlerTask $crawlerTask, \DOMDocument $dom): CrawlerPage
    {
        $internalLinks = $this->crawlerParseService->getLinks($dom, $crawlerTask->hostname, PageLinkTypeEnum::INTERNAL);
        $externalLinks = $this->crawlerParseService->getLinks($dom, $crawlerTask->hostname, PageLinkTypeEnum::EXTERNAL);

        $crawlerPage->internal_links_unique_count = count($internalLinks);
        $crawlerPage->external_links_unique_count = count($externalLinks);

        $crawlerPage->images_unique_count = $this->crawlerParseService->getImagesCount($dom);
        $crawlerPage->words_count = $this->crawlerParseService->getWordsCount($dom);
        $crawlerPage->title_length = $this->crawlerParseService->getTitleLength($dom);

        return $crawlerPage;
    }

    protected function enqueueValidUrls(CrawlerPage $parentPage, CrawlerTask $crawlerTask, \DOMDocument $dom): void
    {
        $internalLinks = $this->crawlerParseService->getLinks($dom, $crawlerTask->hostname, PageLinkTypeEnum::INTERNAL);

        foreach ($internalLinks as $url) {
            $validatedUrl = $this->validateUrl($parentPage, $url);

            if ($validatedUrl && $this->notEnqueuedYet($crawlerTask, $validatedUrl)) {
                $this->createCrawlerPage($crawlerTask, $validatedUrl);
            }
        }
    }

    protected function validateUrl(CrawlerPage $parentPage, string $url): ?string
    {
        if ((strlen($url) === 1 && $url[0] === '/') || $url[0] === '#') {
            return null;
        }

        // prepend a relative URL to an appropriate absolute URL
        if ($url[0] === '/') {
            $parentUrl = parse_url($parentPage->url);
            $url = $parentUrl['scheme'].'://'.$parentUrl['host'].$url;
        }

        // validate an absolute URL
        $validator = Validator::make(['url' => $url], CrawlerTaskRequest::rules());
        if ($validator->fails()) {
            return null;
        }

        return $url;
    }

    // check if a URL wasn't already enqueued in the current crawler task
    protected function notEnqueuedYet(CrawlerTask $crawlerTask, string $url): bool
    {
        return CrawlerPage::where([
            'crawler_task_id' => $crawlerTask->id,
            'url' => $url,
        ])->doesntExist();
    }
}
