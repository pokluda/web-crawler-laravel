<?php

namespace App\Events;

use App\Models\CrawlerTask;
use App\Http\Resources\CrawlerTaskResource;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CrawlerTaskUpdatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $queue = 'broadcasts';

    private CrawlerTask $crawlerTask;

    public function __construct($crawlerTask)
    {
        $this->crawlerTask = $crawlerTask;
    }

    public function broadcastOn(): string
    {
        return 'crawler.task.' . $this->crawlerTask->id;
    }

    public function broadcastWith(): array
    {
        return (new CrawlerTaskResource($this->crawlerTask))->toArray(request());
    }
}
