<?php

namespace App\Enums;

enum CrawlerTaskStatusEnum: string {
    case PROCESSING = 'processing';
    case FINISHING = 'finishing';
    case FINISHED = 'finished';
    case ERROR = 'error';
}
