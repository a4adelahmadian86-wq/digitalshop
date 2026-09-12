<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StorageApiController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Middleware\StorageApiKey;

Route::middleware('auth:sanctum')->get('/user', fn (Request $request) => $request->user());

Route::middleware(['auth:sanctum', 'ability:products:create'])
    ->post('/products', [ProductApiController::class, 'store'])
    ->name('api.products.store');

Route::prefix('storage')->middleware(StorageApiKey::class)->group(function () {
    Route::get('/test', [StorageApiController::class, 'test'])->name('api.storage.test');
    Route::post('/upload', [StorageApiController::class, 'upload'])->name('api.storage.upload');
    Route::get('/exists', [StorageApiController::class, 'exists'])->name('api.storage.exists');
    Route::get('/download', [StorageApiController::class, 'download'])->name('api.storage.download');
    Route::delete('/delete', [StorageApiController::class, 'delete'])->name('api.storage.delete');
});
