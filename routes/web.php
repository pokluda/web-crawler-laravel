<?php

use App\Http\Controllers\CrawlerTaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CrawlerTaskController::class, 'form'])->name('crawler-tasks.form');

Route::post('/crawler-tasks', [CrawlerTaskController::class, 'store'])->name('crawler-tasks.store');

Route::get('/crawler-tasks/{crawlerTask}', [CrawlerTaskController::class, 'show'])->name('crawler-tasks.show');

Route::post('/crawler-tasks/{crawlerTask}/finish', [CrawlerTaskController::class, 'finish'])->name('crawler-tasks.finish');
