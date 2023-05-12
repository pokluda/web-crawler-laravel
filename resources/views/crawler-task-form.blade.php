<!DOCTYPE html>
<html>
  <x-header/>
  <body>

    <section class="section">
      <div class="container">
        <h1 class="title is-1">
          Web Crawler
        </h1>
        <div>
          <form action="/crawler-tasks" method="post">
            @csrf

            <div class="field is-horizontal has-addons">
              <div class="control is-expanded has-icons-left">
                <input
                name="url"
                type="url"
                required
                minlength="{{ Config::get('crawler.task.min_url_length'); }}"
                maxlength="{{ Config::get('crawler.task.max_url_length'); }}"
                pattern="https?://.*"
                placeholder="Entry point HTTP(S) link, please."
                spellcheck="false"
                value="{{ old('url'); }}"
                class="input is-large is-fullwidth @error('url') is-danger @enderror">

                <span class="icon is-left">
                  <i class="mdi mdi-link-variant"></i>
                </span>
              </div>
              <div class="control">
                <button class="button is-large is-primary has-text-weight-bold">
                  <span class="icon is-left">
                    <i class="mdi mdi-rocket-launch"></i>
                  </span>
                  <span>GO!</span>
                </button>
              </div>
            </div>
            @error('url')
                <div class="help is-danger is-size-6 has-text-weight-bold">
                    {{ $message }}
                </div>
              @enderror
          </form>
        </div>
      </div>
    </section>

  </body>
</html>
