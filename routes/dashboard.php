<?php

use App\Http\Controllers\AdminAccessController;
use App\Http\Controllers\AdminAutomationController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->middleware(['auth', 'dashboard.access'])
    ->group(function () {
        Route::get('/access', [AdminAccessController::class, 'index'])
            ->middleware('permission:roles.view')
            ->name('admin.access.index');

        Route::put('/access/{role}', [AdminAccessController::class, 'update'])
            ->middleware('permission:roles.update')
            ->name('admin.access.update');

        Route::get('/automation', [AdminAutomationController::class, 'index'])
            ->middleware('permission:automation.view')
            ->name('admin.automation.index');

        Route::put('/automation/{automation}', [AdminAutomationController::class, 'update'])
            ->middleware('permission:automation.manage')
            ->name('admin.automation.update');

        Route::post('/automation/{automation}/run', [AdminAutomationController::class, 'run'])
            ->middleware('permission:automation.manage')
            ->name('admin.automation.run');
    });