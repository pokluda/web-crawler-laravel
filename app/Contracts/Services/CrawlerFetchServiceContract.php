<?php

namespace App\Contracts\Services;

use App\Contracts\Dtos\FetchedPageDto;

interface CrawlerFetchServiceContract
{
    public function fetchPage(string $url): FetchedPageDto;
}
