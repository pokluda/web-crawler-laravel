<?php

namespace App\Models;

use App\Enums\CrawlerPageStatusEnum;
use App\Enums\CrawlerTaskStatusEnum;
use App\Events\CrawlerTaskUpdatedEvent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Config;

class CrawlerTask extends Model
{
    use HasUlids, HasFactory;

    protected $fillable = ['url'];

    protected $casts = [
        'status' => CrawlerTaskStatusEnum::class,
    ];

    protected $attributes = [
        'status' => CrawlerTaskStatusEnum::PROCESSING,
    ];

    public function pages(): HasMany
    {
        return $this->hasMany(CrawlerPage::class);
    }

    public function getHostnameAttribute(): string
    {
        return parse_url($this->url, PHP_URL_HOST);
    }

    public function finishedPages(): Collection
    {
        // get the latest pages relationship data (to avoid caching issues)
        $this->load('pages');

        return $this->pages->where('status', CrawlerPageStatusEnum::FINISHED);
    }

    public function activePagesCount(): int
    {
        // get the latest pages relationship data (to avoid caching issues)
        $this->load('pages');

        return $this->pages->whereIn('status', [
            CrawlerPageStatusEnum::ENQUEUED,
            CrawlerPageStatusEnum::PROCESSING,
            CrawlerPageStatusEnum::FETCHED,
        ])->count();
    }

    public function isActive(): bool
    {
        return $this->status === CrawlerTaskStatusEnum::PROCESSING
            && $this->activePagesCount() > 0
            && !$this->isThresholdReached();
    }

    public function isNotActive(): bool
    {
        return !$this->isActive();
    }

    public function isThresholdReached(): bool
    {
        if ($this->finishedPages()->count() >= Config::get('crawler.task.crawled_pages_threshold')) {
            return true;
        } else {
            return false;
        }
    }

    public function initiateFinish(): bool
    {
        if ($this->status === CrawlerTaskStatusEnum::PROCESSING) {
            $this->status = CrawlerTaskStatusEnum::FINISHING;
            $this->save();

            return true;
        }

        return false;
    }

    public function finish(): bool
    {
        if ($this->status !== CrawlerTaskStatusEnum::FINISHED) {
            $this->status = CrawlerTaskStatusEnum::FINISHED;
            $this->save();

            event(new CrawlerTaskUpdatedEvent($this));

            return true;
        }

        return false;
    }
}
