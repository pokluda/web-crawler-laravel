<?php

namespace App\Services;

use App\Contracts\Services\CrawlerParseServiceContract;
use App\Enums\PageLinkTypeEnum;

class CrawlerParseService implements CrawlerParseServiceContract
{
    public function getPageDom(string $pageContent)
    {
        libxml_use_internal_errors(true);

        $dom = new \DOMDocument();
        $dom->loadHTML($pageContent);

        return $dom;
    }

    public function getLinks(
        \DOMDocument $dom,
        string $crawlerTaskHostname,
        $type = PageLinkTypeEnum::ALL,
        $unique = true
    ): array
    {
        $links = array_map(
            fn (\DOMElement $img): String => $img->getAttribute('href'),
            iterator_to_array($dom->getElementsByTagName('a'))
        );

        if ($unique) {
            $links = array_unique($links);
        }

        $filteredLinks = array_filter($links, function($link) use ($type, $crawlerTaskHostname) {

            if (strlen($link) === 0) {
                return false;
            }

            if ($type === PageLinkTypeEnum::ALL) {
                return true;
            } else {
                $linkHostname = parse_url($link, PHP_URL_HOST);

                if ($type === PageLinkTypeEnum::INTERNAL) {
                    return
                        $link[0] === '/' ||
                        $link[0] === '#' ||
                        $linkHostname === $crawlerTaskHostname;
                } else if ($type === PageLinkTypeEnum::EXTERNAL) {
                    return
                        $link[0] !== '/' &&
                        $link[0] !== '#' &&
                        $linkHostname !== $crawlerTaskHostname;
                }
            }

            return false;
        });

        return $filteredLinks;
    }

    public function getImagesCount(\DOMDocument $dom, $unique = true): int
    {
        $imageUrls = array_map(
            fn (\DOMElement $img): String => $img->getAttribute('src'),
            iterator_to_array($dom->getElementsByTagName('img'))
        );

        if ($unique) {
            $imageUrls = array_unique($imageUrls);
        }

        return count($imageUrls);
    }

    public function getWordsCount(\DOMDocument $dom): int
    {
        $body = $dom->getElementsByTagName('body')->item(0)->textContent;

        // strip all HTML tags
        $bodyStripped = strip_tags($body);

        return str_word_count($bodyStripped);
    }

    public function getTitleLength(\DOMDocument $dom): int
    {
        $titleNode = $dom->getElementsByTagName('title')->item(0);

        if ($titleNode) {
            return strlen($titleNode->textContent);
        } else {
            return 0;
        }
    }
}
