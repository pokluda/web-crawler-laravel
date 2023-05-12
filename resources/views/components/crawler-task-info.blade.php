<div class="block">
    <h1 class="title">
    <a href="<?= $crawlerTaskResource['url']; ?>" target="_blank">
        <?= $crawlerTaskResource['url']; ?>
    </a>
    </h1>
</div>

<div class="block">
    <div class="field is-grouped is-grouped-multiline">
    <div class="control" title="Started At">
        <div class="tags has-addons is-large">
        <span class="tag is-dark is-large">
            <span class="icon is-left">
            <i class="mdi mdi-calendar-month-outline"></i>
            </span>
        </span>
        <span class="tag is-info is-light is-large has-text-weight-bold">
            <?= $crawlerTaskResource['created_at']; ?>
        </span>
        </div>
    </div>
    <div class="control" title="Time Elapsed">
        <div class="tags has-addons is-large">
        <span class="tag is-dark is-large">
            <span class="icon is-left">
            <i class="mdi mdi-timer-outline"></i>
            </span>
        </span>
        <span id="task-duration" class="tag is-info is-large has-text-weight-bold">
            <?= $crawlerTaskResource['elapsed_time_seconds']; ?>s
        </span>
        </div>
    </div>
    <div class="control" title="Task Status">
        <div class="tags has-addons is-large">
        <span class="tag is-dark is-large">
            <span class="icon is-left">
            <i class="mdi mdi-progress-check"></i>
            </span>
        </span>
        <span id="task-status" class="tag is-large has-text-weight-bold <?= ($crawlerTaskResource['status'] === 'finished') ? 'is-success' : 'is-light'; ?>">
            <?= $crawlerTaskResource['status']; ?>
        </span>
        </div>
    </div>
    <div class="control" title="Finish Task">
        <button
        id="button-status"
        class="button has-text-weight-bold"
        disabled>

        <span class="icon is-left">
            <i class="mdi mdi-link-variant"></i>
        </span>
        <span>FINISH</span>
        </button>
    </div>
    </div>
</div>

<div class="block">
    <progress
        id="task-progress"
        class="progress is-success"
        value="<?= $crawlerTaskResource['pages_sum']; ?>"
        max="<?= ($crawlerTaskResource['status'] === 'finished') ? $crawlerTaskResource['pages_sum'] : Config::get('crawler.task.crawled_pages_threshold'); ?>">
    </progress>
</div>
