<?php

use App\Enums\CrawlerPageStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crawler_pages', function (Blueprint $table)
        {
            $table->primary('id');
            $table->ulid('id');
            $table->foreignUlid('crawler_task_id')->constrained('crawler_tasks');

            $table->enum('status', array_column(
                CrawlerPageStatusEnum::cases(), 'value'
            ))->default(CrawlerPageStatusEnum::ENQUEUED->value);

            $table->string('url', Config::get('crawler.task.max_url_length'));
            $table->unsignedSmallInteger('http_status_code')->nullable();
            $table->time('page_load_duration', 6)->nullable();
            $table->unsignedInteger('internal_links_unique_count')->nullable();
            $table->unsignedInteger('external_links_unique_count')->nullable();
            $table->unsignedInteger('images_unique_count')->nullable();
            $table->unsignedInteger('words_count')->nullable();
            $table->unsignedInteger('title_length')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crawler_pages');
    }
};
