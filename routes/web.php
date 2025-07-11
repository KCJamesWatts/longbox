<?php

use App\Http\Controllers\PublisherController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Publisher routes
Route::get('/publishers', [PublisherController::class, 'list'])->name('publishers.list');
