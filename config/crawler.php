<?php

return [
    'task' => [
        'crawled_pages_threshold' => env('CRAWLER_TASK_PROCESSED_PAGES_THRESHOLD', 100),
        'min_url_length' => env('CRAWLER_MIN_URL_LENGTH', 10),
        'max_url_length' => env('CRAWLER_MAX_URL_LENGTH', 2_000),
    ],

    'page' => [

    ],
];
