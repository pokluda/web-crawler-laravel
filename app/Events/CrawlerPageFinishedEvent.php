<?php

namespace App\Events;

use App\Models\CrawlerPage;
use App\Http\Resources\CrawlerPageResource;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CrawlerPageFinishedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $queue = 'broadcasts';

    private CrawlerPage $crawlerPage;

    public function __construct($crawlerPage)
    {
        $this->crawlerPage = $crawlerPage;
    }

    public function broadcastOn(): string
    {
        return 'crawler.page.' . $this->crawlerPage->crawlerTask->id;
    }

    public function broadcastWith(): array
    {
        return (new CrawlerPageResource($this->crawlerPage))->toArray(request());
    }
}
