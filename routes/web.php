<?php

use App\Http\Controllers\PublisherController;
use App\Http\Controllers\SeriesController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Publisher routes
Route::controller(PublisherController::class)->prefix('/publisher')->group(function () {
    Route::get('/', 'listView')->name('publisher.list');
    Route::get('/add', 'addView')->name('publisher.add');
    Route::post('/add', 'post')->name('publisher.post');
    Route::get('/{id}', 'showView')->name('publisher.get');
    Route::put('/{id}', 'put')->name('publisher.put');
    Route::delete('/{id}/delete', 'delete')->name('publisher.delete');
});

// Series routes
Route::controller(SeriesController::class)->prefix('/series')->group(function () {
    Route::get('/', 'listView')->name('series.list');
    Route::get('/add', 'addView')->name('series.add');
    Route::post('/add', 'post')->name('series.post');
    Route::get('/{id}', 'showView')->name('series.get');
    Route::put('/{id}', 'put')->name('series.put');
    Route::delete('/{id}/delete', 'delete')->name('series.delete');
});