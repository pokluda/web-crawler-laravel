# Web Crawler in Laravel

A simple web crawler using the Laravel Framework. As such it utilizes Laravel's components Eloquent, HTTP Client, job queues, broadcasting (Pusher, Echo) and others.

## Architecture

### Models

[`CrawlerTask`](App/Models/CrawlerTask.php) - a new task is created for every run of the web crawler.

[`CrawlerPage`](App/Models/CrawlerPage.php) - each crawled page is attached to a task and keeps its parsed data.

### Queues

`jobs` - entry point URL and subsequent (internal) links are enqueued as [`CrawlerPage`](App/Models/CrawlerPage.php) and processed asynchronously.

`broadcasts` - processed pages broadcast the [`CrawlerPageFinishedEvent`](App/Events/CrawlerPageFinishedEvent.php) and any updates of task data broadcast the [`CrawlerTaskUpdatedEvent`](App/Events/CrawlerTaskUpdatedEvent.php).

## ToDo
- tests, tests and more tests.
- better error handling/logging.
- use Laravel Horizon for queue orchestration.
- use Vue.js instead of vanilla JS.
