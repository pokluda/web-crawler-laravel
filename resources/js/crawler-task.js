let taskId = window.windowTaskId;
let initTaskStatus = window.windowTaskStatus;
let currentTaskStatus;
let intervalId;
let startDateTime = Date.now();

let taskDurationElement = document.getElementById('task-duration');
let taskStatusElement = document.getElementById('task-status');
let buttonStatusElement = document.getElementById('button-status');
let taskProgressElement = document.getElementById('task-progress');
let taskPagesSumElement = document.getElementById('task-pages-sum');
let taskInternalLinksSumElement = document.getElementById('task-internal-links-unique-sum');
let taskExternalLinksSumElement = document.getElementById('task-external-links-unique-sum');
let taskImagesSumElement = document.getElementById('task-images-unique-sum');
let taskPageLoadDurationAvgElement = document.getElementById('task-page-load-duration-avg');
let taskWordsAvgElement = document.getElementById('task-words-avg');
let taskTitleLengthAvgElement = document.getElementById('task-title-length-avg');
let tablePagesElement = document.getElementById('table-pages');


if (initTaskStatus === 'processing') {
    initChannelsAndListeners();

    buttonStatusElement.addEventListener('click', finishTask);
}

setTaskStatus(initTaskStatus);

function initChannelsAndListeners()
{
    let channelTask = Echo.channel('crawler.task.' + taskId);
    let channelPage = Echo.channel('crawler.page.' + taskId);

    channelTask.listen('CrawlerTaskUpdatedEvent', (event) => {
        updateCrawlerTaskData(event);
    });

    channelPage.listen('CrawlerPageFinishedEvent', (event) => {
        addCrawlerPageData(event)
    });
}

function updateCrawlerTaskData(event)
{
    // avoid status flicker when finishing
    if (currentTaskStatus !== 'finishing' ||
        (currentTaskStatus === 'finishing' && event.status === 'finished'))
    {
        taskStatusElement.textContent = event.status;
    }

    taskProgressElement.value = event.pages_sum;
    taskPagesSumElement.textContent = event.pages_sum;
    taskInternalLinksSumElement.textContent = event.internal_links_unique_sum;
    taskExternalLinksSumElement.textContent = event.external_links_unique_sum;
    taskImagesSumElement.textContent = event.images_unique_sum;
    taskPageLoadDurationAvgElement.textContent = event.page_load_duration_avg + 's';
    taskWordsAvgElement.textContent = event.words_avg;
    taskTitleLengthAvgElement.textContent = event.title_length_avg;

    setTaskStatus(event.status);
}

function addCrawlerPageData(event)
{
    let newRow = tablePagesElement.insertRow();

    addCrawlerPageDataCell(newRow, document.createTextNode(event.http_status_code), 'has-text-right');
    addCrawlerPageDataCell(newRow, document.createTextNode(event.page_load_duration + 's'), 'has-text-right');
    addCrawlerPageDataCell(newRow, document.createTextNode(event.internal_links_unique_count), 'has-text-right');
    addCrawlerPageDataCell(newRow, document.createTextNode(event.external_links_unique_count), 'has-text-right');
    addCrawlerPageDataCell(newRow, document.createTextNode(event.images_unique_count), 'has-text-right');
    addCrawlerPageDataCell(newRow, document.createTextNode(event.words_count), 'has-text-right');
    addCrawlerPageDataCell(newRow, document.createTextNode(event.title_length), 'has-text-right');

    // make URL clickable
    let urlAnchor = document.createElement('a');
    urlAnchor.setAttribute('href', event.url);
    urlAnchor.setAttribute('target', '_blank');

    let urlAnchorText = document.createTextNode(event.url);
    urlAnchor.appendChild(urlAnchorText);

    addCrawlerPageDataCell(newRow, urlAnchor);
}

function addCrawlerPageDataCell(rowElement, cellTextElement, addClass)
{
    let cellElement = rowElement.insertCell();

    if (addClass) {
        cellElement.classList.add(addClass);
    }

    cellElement.appendChild(cellTextElement);
}

async function finishTask()
{
    setTaskStatus('finishing');

    try {
        const finishTaskResponse = await axios.post('/crawler-tasks/' + taskId + '/finish');
    } catch (error) {
        console.error(error);
    }
}

function setTaskStatus(taskStatus)
{
    let taskStatusClasses = ['is-light', 'is-info', 'is-success', 'is-warning', 'is-error'];
    let buttonStatusClasses = ['is-warning', 'is-loading'];

    if (currentTaskStatus !== taskStatus) {

        currentTaskStatus = taskStatus;

        let currentTaskStatusClasses;
        let currentButtonStatusClasses;

        switch (taskStatus) {
            case 'processing':
                currentTaskStatusClasses = ['is-info'];
                currentButtonStatusClasses = ['is-warning'];

                buttonStatusElement.removeAttribute('disabled');
                break;
            case 'finishing':
                currentTaskStatusClasses = ['is-warning'];
                currentButtonStatusClasses = ['is-warning', 'is-loading'];

                buttonStatusElement.setAttribute('disabled', '');
                taskStatusElement.textContent = 'finishing';
                break;
            case 'finished':
                currentTaskStatusClasses = ['is-success'];
                currentButtonStatusClasses = [];

                buttonStatusElement.setAttribute('disabled', '');
                taskProgressElement.setAttribute('max', parseInt(taskPagesSumElement.textContent, 10));

                if (intervalId) {
                    clearInterval(intervalId);
                }

                break;
        default:
            currentTaskStatusClasses = ['is-light'];
            currentButtonStatusClasses = [];
        }

        // adjust classes on the task and button status elements
        taskStatusElement.classList.add(...currentTaskStatusClasses);
        buttonStatusElement.classList.add(...currentButtonStatusClasses);

        taskStatusElement.classList.remove(
        ...taskStatusClasses.filter(status =>
            currentTaskStatusClasses.includes(status) === false
        )
        );

        buttonStatusElement.classList.remove(
        ...buttonStatusClasses.filter(status =>
            currentButtonStatusClasses.includes(status) === false
        )
        );
    }
}

function updateElapsedTime()
{
    let elapsed = (new Date() - startDateTime);

    taskDurationElement.textContent = Math.round(elapsed / 1000) + 's';
}

if (currentTaskStatus === 'processing') {
    intervalId = setInterval(updateElapsedTime, 1000);
}
