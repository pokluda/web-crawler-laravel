<div class="block">
    <div class="table-container">
        <table class="table is-fullwidth is-striped is-hoverable">
            <thead class="has-background-light">
            <th class="has-text-right">
                <abbr title="HTTP status code">
                    HTTP
                </abbr>
            </th>
            <th class="has-text-right">
                <abbr title="Page load duration">
                    Time
                </abbr>
            </th>
            <th class="has-text-right">
                <abbr title="Number of unique internal links">
                    # IN Links
                </abbr>
            </th>
            <th class="has-text-right">
                <abbr title="Number of unique internal links">
                    # EX Links
                </abbr>
            </th>
            <th class="has-text-right">
                <abbr title="Number of unique images">
                    # Images
                </abbr>
            </th>
            <th class="has-text-right">
                <abbr title="Number of words">
                    # Words
                </abbr>
            </th>
            <th class="has-text-right">
                Title Length
            </th>
            <th>
                URL
            </th>
            </thead>
            <tbody id="table-pages">
                @foreach ($finishedCrawlerPagesResource as $pageResource)
                    <tr>
                        <td class="has-text-right">
                            {{ $pageResource['http_status_code'] }}
                        </td>
                        <td class="has-text-right">
                            {{ $pageResource['page_load_duration'] }}s
                        </td>
                        <td class="has-text-right">
                            {{ $pageResource['internal_links_unique_count'] }}
                        </td>
                        <td class="has-text-right">
                            {{ $pageResource['external_links_unique_count'] }}
                        </td>
                        <td class="has-text-right">
                            {{ $pageResource['images_unique_count'] }}
                        </td>
                        <td class="has-text-right">
                            {{ $pageResource['words_count'] }}
                        </td>
                        <td class="has-text-right">
                            {{ $pageResource['title_length'] }}
                        </td>
                        <td>
                            <a href="{{ $pageResource['url'] }}" target="_blank">
                                {{ $pageResource['url'] }}
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
