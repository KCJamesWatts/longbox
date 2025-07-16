<?php

use App\Http\Controllers\PublisherController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Publisher routes
Route::get('/publishers/add', [PublisherController::class, 'addView'])->name('publisher.add');
Route::post('/publishers/add', [PublisherController::class, 'post'])->name('publisher.post');
Route::get('/publishers', [PublisherController::class, 'listView'])->name('publisher.list');
Route::get('/publishers/{id}', [PublisherController::class, 'showView'])->name('publisher.get');
Route::put('/publishers/{id}', [PublisherController::class, 'put'])->name('publisher.put');
Route::delete('/publishers/{id}/delete', [PublisherController::class, 'delete'])->name('publisher.delete');