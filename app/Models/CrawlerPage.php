<?php

namespace App\Models;

use App\Enums\CrawlerPageStatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrawlerPage extends Model
{
    use HasUlids, HasFactory;

    protected $fillable = ['crawler_task_id', 'url'];

    protected $casts = [
        'status' => CrawlerPageStatusEnum::class,
    ];

    protected $attributes = [
        'status' => CrawlerPageStatusEnum::ENQUEUED,
    ];

    public function crawlerTask(): BelongsTo
    {
        return $this->belongsTo(CrawlerTask::class);
    }

    protected function pageLoadDuration(): Attribute
    {
        return Attribute::make(
            get: fn (?string $timeString) => $this->timeToMicroseconds($timeString)
        );
    }

    private function timeToMicroseconds(?string $timeString): ?float
    {
        if ($timeString) {
            $pageLoadDurationSeconds = Carbon::createFromTimeString($timeString)->secondsSinceMidnight();
            $pageLoadDurationMicroSeconds = Carbon::createFromTimeString($timeString)->format('u');

            return $pageLoadDurationSeconds . '.' . $pageLoadDurationMicroSeconds;
        } else {
            return null;
        }
    }
}
