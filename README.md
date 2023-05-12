# Web Crawler in Laravel

A simple web crawler using the Laravel Framework. As such it utilizes Laravel's components Eloquent, HTTP Client, job queues, broadcasting (Pusher, Echo) and others.

## Architecture

### Models

[`CrawlerTask`](app/Models/CrawlerTask.php) - a new task is created for every run of the web crawler.

[`CrawlerPage`](app/Models/CrawlerPage.php) - each crawled page is attached to a task and keeps its parsed data.

### Queues

`jobs` - entry point URL and subsequent (internal) links are enqueued as [`CrawlerPage`](app/Models/CrawlerPage.php) and processed asynchronously.

`broadcasts` - processed pages broadcast the [`CrawlerPageFinishedEvent`](app/Events/CrawlerPageFinishedEvent.php) and any updates of task data broadcast the [`CrawlerTaskUpdatedEvent`](app/Events/CrawlerTaskUpdatedEvent.php).

## Running locally

```
git clone https://github.com/pokluda/web-crawler-laravel.git

cp .env.example .env

composer require laravel/sail --dev

php artisan sail:install --with=mysql,redis,meilisearch,mailpit,selenium

./vendor/bin/sail up

php artisan key:generate

./vendor/bin/sail artisan migrate

./vendor/bin/sail composer install

./vendor/bin/sail npm install

./vendor/bin/sail npm run build

./vendor/bin/sail artisan queue:work --queue=jobs

./vendor/bin/sail artisan queue:work --queue=broadcasts
```

## ToDo
- tests, tests and more tests.
- better error handling/logging.
- use Laravel Horizon for queue orchestration.
- use Vue.js instead of vanilla JS.
