<?php

use App\Enums\CrawlerTaskStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crawler_tasks', function (Blueprint $table)
        {
            $table->primary('id');
            $table->ulid('id');

            $table->enum('status', array_column(
                CrawlerTaskStatusEnum::cases(), 'value'
            ))->default(CrawlerTaskStatusEnum::PROCESSING->value);

            $table->string('url', Config::get('crawler.task.max_url_length'));
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crawler_tasks');
    }
};
