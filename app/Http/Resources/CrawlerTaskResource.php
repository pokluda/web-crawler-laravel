<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CrawlerTaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $finishedPages = $this->finishedPages();

        return [
            'id' => $this->id,
            'created_at' => $this->created_at->format('H:i:s d-m-Y'),
            'elapsed_time_seconds' => $this->created_at->diffInSeconds($this->updated_at),
            'url' => $this->url,
            'status' => $this->status->value,
            'pages_sum' => $finishedPages->count(),
            'internal_links_unique_sum' => $finishedPages->sum('internal_links_unique_count'),
            'external_links_unique_sum' => $finishedPages->sum('external_links_unique_count'),
            'images_unique_sum' => $finishedPages->sum('images_unique_count'),
            'words_avg' => round($finishedPages->avg('words_count') ?? 0, 1),
            'title_length_avg' => round($finishedPages->avg('title_length') ?? 0, 1),
            'page_load_duration_avg' => round($finishedPages->avg('page_load_duration') ?? 0, 1),
        ];
    }
}
