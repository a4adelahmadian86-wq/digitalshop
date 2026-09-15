<?php

use App\Http\Controllers\AdminAccessController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('/access', [AdminAccessController::class, 'index'])
            ->middleware('permission:roles.view')
            ->name('admin.access.index');

        Route::put('/access/{role}', [AdminAccessController::class, 'update'])
            ->middleware('permission:roles.update')
            ->name('admin.access.update');
    });