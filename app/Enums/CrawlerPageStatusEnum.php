<?php

namespace App\Enums;

enum CrawlerPageStatusEnum: string {
    case ENQUEUED = 'enqueued';
    case PROCESSING = 'processing';
    case FETCHED = 'fetched';
    case FINISHED = 'finished';
    case ERROR = 'error';
}
