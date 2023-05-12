<?php

namespace App\Contracts\Dtos;

readonly class FetchedPageDto
{
    public function __construct(
        public int $statusCode,
        public ?float $duration,
        public string $content,
    ) {}
}
