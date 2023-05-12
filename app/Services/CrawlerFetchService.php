<?php

namespace App\Services;

use App\Contracts\Dtos\FetchedPageDto;
use App\Contracts\Services\CrawlerFetchServiceContract;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\TransferStats;

class CrawlerFetchService implements CrawlerFetchServiceContract
{
    public function fetchPage(string $url): FetchedPageDto
    {
        $responseDuration = null;

        // use underlying Guzzle's "on_stats" option to get the total time of a request
        $response = Http::withOptions([
            'on_stats' => function (TransferStats $stats) use (&$responseDuration) {
                $responseDuration = $stats->getTransferTime();
            },
        ])->get($url);

        $crawledPage = new FetchedPageDto(
            $response->status(),
            $responseDuration,
            $response->body()
        );

        return $crawledPage;
    }
}
