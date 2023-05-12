<?php

namespace App\Http\Controllers;

use App\Http\Requests\CrawlerTaskRequest;
use App\Http\Resources\CrawlerTaskResource;
use App\Http\Resources\CrawlerPageResource;
use App\Models\CrawlerTask;
use App\Contracts\Services\CrawlerTaskServiceContract;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class CrawlerTaskController extends Controller
{
    use ValidatesRequests;

    public function form(): View
    {
        return view('crawler-task-form');
    }

    public function store(
        CrawlerTaskRequest $request,
        CrawlerTaskServiceContract $crawlerTaskService): RedirectResponse
    {
        $validated = $request->safe()->only(['url']);

        $crawlerTask = $crawlerTaskService->create($validated['url']);

        return redirect()->action(
            [CrawlerTaskController::class, 'show'],
            [$crawlerTask]
        );
    }

    public function show(CrawlerTask $crawlerTask): View
    {
        return view('crawler-task', [
            'crawlerTaskResource' =>
                (new CrawlerTaskResource($crawlerTask))->toArray(request()),
            'finishedCrawlerPagesResource' =>
                CrawlerPageResource::collection($crawlerTask->finishedPages())->toArray(request()),
        ]);
    }

    public function finish(
        CrawlerTask $crawlerTask,
        CrawlerTaskServiceContract $crawlerTaskService): JsonResponse
    {
        $finishInitiated = $crawlerTaskService->initiateFinish($crawlerTask);

        return response()->json($finishInitiated);
    }
}
