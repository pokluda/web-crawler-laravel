<div class="block">
    <div class="columns has-text-centered">
        <div class="column">
            <div class="box">
                <p class="is-size-4">
                    <abbr title="Crawled pages total">
                        Σ pages
                    </abbr>
                </p>
                <p id="task-pages-sum" class="is-size-3 has-text-info has-text-weight-bold">
                    <?= $crawlerTaskResource['pages_sum']; ?>
                </p>
            </div>
        </div>
        <div class="column">
            <div class="box">
                <p class="is-size-4">
                    <abbr title="Unique internal links total">
                        Σ INT Links
                    </abbr>
                </p>
                <p id="task-internal-links-unique-sum" class="is-size-3 has-text-info has-text-weight-bold">
                    <?= $crawlerTaskResource['internal_links_unique_sum']; ?>
                </p>
            </div>
        </div>
        <div class="column">
            <div class="box">
                <p class="is-size-4">
                    <abbr title="Unique external links total">
                        Σ EXT Links
                    </abbr>
                </p>
                <p id="task-external-links-unique-sum" class="is-size-3 has-text-info has-text-weight-bold">
                    <?= $crawlerTaskResource['external_links_unique_sum']; ?>
                </p>
            </div>
        </div>
        <div class="column">
            <div class="box">
                <p class="is-size-4">
                    <abbr title="Unique images total">
                        Σ Images
                    </abbr>
                </p>
                <p id="task-images-unique-sum" class="is-size-3 has-text-info has-text-weight-bold">
                    <?= $crawlerTaskResource['images_unique_sum']; ?>
                </p>
            </div>
        </div>
    </div>

    <div class="columns has-text-centered">
        <div class="column">
            <div class="box">
                <p class="is-size-4">
                    <abbr title="Page load duration average">
                        AVG Time
                    </abbr>
                </p>
                <p id="task-page-load-duration-avg" class="is-size-3 has-text-info has-text-weight-bold">
                    <?= $crawlerTaskResource['page_load_duration_avg']; ?>s
                </p>
            </div>
        </div>
        <div class="column">
            <div class="box">
                <p class="is-size-4">
                    <abbr title="Words average">
                        AVG Words
                    </abbr>
                </p>
                <p id="task-words-avg" class="is-size-3 has-text-info has-text-weight-bold">
                    <?= $crawlerTaskResource['words_avg']; ?>
                </p>
            </div>
        </div>
        <div class="column">
            <div class="box">
                <p class="is-size-4">
                    <abbr title="Title length average">
                        AVG Title Length
                    </abbr>
                </p>
                <p id="task-title-length-avg" class="is-size-3 has-text-info has-text-weight-bold">
                    <?= $crawlerTaskResource['title_length_avg']; ?>
                </p>
            </div>
        </div>
    </div>
</div>
