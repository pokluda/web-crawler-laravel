<?php

namespace App\Contracts\Services;

use App\Enums\PageLinkTypeEnum;

interface CrawlerParseServiceContract
{
    public function getPageDom(string $pageContent);
    public function getLinks(\DOMDocument $dom, string $crawlerTaskHostname, $type = PageLinkTypeEnum::ALL, $unique = true): array;
    public function getImagesCount(\DOMDocument $dom, $unique = true): int;
    public function getWordsCount(\DOMDocument $dom): int;
    public function getTitleLength(\DOMDocument $dom): int;
}
