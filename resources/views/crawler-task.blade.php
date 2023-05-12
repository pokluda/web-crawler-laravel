<!DOCTYPE html>
<html>
  <x-header/>

  @vite('resources/js/app.js')

  <script>
    window.windowTaskId = '<?= $crawlerTaskResource['id']; ?>';
    window.windowTaskStatus = '<?= $crawlerTaskResource['status']; ?>';
  </script>

  <body>

    <section class="section">
      <div class="container">

        <x-crawler-task-info :crawlerTaskResource="$crawlerTaskResource"/>

        <x-crawler-task-aggregates :crawlerTaskResource="$crawlerTaskResource"/>

        <x-crawler-task-pages-list :finishedCrawlerPagesResource="$finishedCrawlerPagesResource"/>

      </div>
    </section>

  </body>

  @vite('resources/js/crawler-task.js')

</html>
