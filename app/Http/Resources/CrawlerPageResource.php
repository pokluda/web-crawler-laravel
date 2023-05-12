<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CrawlerPageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'url' => $this->url,
            'http_status_code' => $this->http_status_code,
            'page_load_duration' => round($this->page_load_duration, 1),
            'internal_links_unique_count' => $this->internal_links_unique_count,
            'external_links_unique_count' => $this->external_links_unique_count,
            'images_unique_count' => $this->images_unique_count,
            'words_count' => $this->words_count,
            'title_length' => $this->title_length,
        ];
    }
}
