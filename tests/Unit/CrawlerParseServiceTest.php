<?php

namespace Tests\Unit;

use App\Contracts\Services\CrawlerParseServiceContract;
use App\Services\CrawlerParseService;
use PHPUnit\Framework\TestCase;

class CrawlerParseServiceTest extends TestCase
{
    private CrawlerParseServiceContract $crawlerParseService;

    public function setUp(): void
    {
        parent::setUp();
        $this->crawlerParseService = new CrawlerParseService();
    }

    public function test_get_page_dom(): void
    {
        $domFromService = $this->crawlerParseService->getPageDom('<html><head><title>abcdefghi</title><body></body></html>');

        $dom = new \DOMDocument();
        $dom->loadHTML('<html><head><title>abcdefghi</title><body></body></html>');

        $this->assertEquals($dom, $domFromService);
    }

    public function test_images_count(): void
    {
        $dom = new \DOMDocument();

        $dom->loadHTML('<html></html>');
        $this->assertEquals(0, $this->crawlerParseService->getImagesCount($dom));

        $dom->loadHTML('<img src="a"><img src="b"><img src="a">');
        $this->assertEquals(2, $this->crawlerParseService->getImagesCount($dom));
        $this->assertEquals(3, $this->crawlerParseService->getImagesCount($dom, false));
    }

    public function test_words_count(): void
    {
        $dom = new \DOMDocument();

        $dom->loadHTML('<html><head><title></title><body></body></html>');
        $this->assertEquals(0, $this->crawlerParseService->getWordsCount($dom));

        $dom->loadHTML('<html><head><title>not counted</title><body>only words in the <strong>body</strong> tag are</body></html>');
        $this->assertEquals(7, $this->crawlerParseService->getWordsCount($dom));
    }

    public function test_title_length(): void
    {
        $dom = new \DOMDocument();

        $dom->loadHTML('<html></html>');
        $this->assertEquals(0, $this->crawlerParseService->getTitleLength($dom));

        $dom->loadHTML('<html><head><title>abcdefghi</title><body></body></html>');
        $this->assertEquals(9, $this->crawlerParseService->getTitleLength($dom));
    }
}
