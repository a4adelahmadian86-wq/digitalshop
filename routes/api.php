```php
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StorageApiController;
use App\Http\Middleware\StorageApiKey;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| These routes are loaded by the RouteServiceProvider.
|
*/

/*
|--------------------------------------------------------------------------
| Sanctum User
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


/*
|--------------------------------------------------------------------------
| Storage API
|--------------------------------------------------------------------------
*/

Route::prefix('storage')
    ->middleware(StorageApiKey::class)
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Test Connection
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/test',
            [StorageApiController::class, 'test']
        )->name('api.storage.test');


        /*
        |--------------------------------------------------------------------------
        | Upload
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/upload',
            [StorageApiController::class, 'upload']
        )->name('api.storage.upload');


        /*
        |--------------------------------------------------------------------------
        | Exists
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/exists',
            [StorageApiController::class, 'exists']
        )->name('api.storage.exists');


        /*
        |--------------------------------------------------------------------------
        | Download
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/download',
            [StorageApiController::class, 'download']
        )->name('api.storage.download');


        /*
        |--------------------------------------------------------------------------
        | Delete
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/delete',
            [StorageApiController::class, 'delete']
        )->name('api.storage.delete');

    });
