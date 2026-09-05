<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReaderSubscriptionController;
use App\Http\Controllers\AdminPanelController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\AdminAiSettingsController;
use App\Http\Controllers\AdminSiteSettingsController;
use App\Http\Controllers\AdminIntegrationSettingsController;
use App\Http\Controllers\AdminApiKeysController;
use App\Http\Controllers\EmailOnboardingController;
use App\Http\Controllers\EmailPhoneController;

Route::middleware('auth')
    ->prefix('account/reader')
    ->group(function () {
        Route::get('/', [ReaderSubscriptionController::class, 'index'])
            ->name('account.reader');

        Route::post('/subscribe', [ReaderSubscriptionController::class, 'purchase'])
            ->name('account.reader.purchase');
    });

Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
    ->name('newsletter.subscribe');

Route::get('/email/start', [EmailOnboardingController::class, 'create'])
    ->name('email.onboarding');

Route::post('/email/start', [EmailOnboardingController::class, 'send'])
    ->name('email.onboarding.send');

Route::get('/email/verify/{token}', [EmailOnboardingController::class, 'verify'])
    ->name('email.onboarding.verify');

Route::get('/email/phone', [EmailOnboardingController::class, 'phone'])
    ->name('email.onboarding.phone');

Route::post('/email/phone', [EmailPhoneController::class, 'send'])
    ->name('email.onboarding.phone.send');

Route::post('/email/phone/verify', [EmailPhoneController::class, 'verify'])
    ->name('email.onboarding.phone.verify');

Route::middleware(['auth', 'role:admin', 'admin.permission'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/ai/settings', [AdminAiSettingsController::class, 'edit'])
            ->name('admin.ai.settings');

        Route::put('/ai/settings', [AdminAiSettingsController::class, 'update'])
            ->name('admin.ai.settings.update');

        Route::get('/settings/general', [AdminSiteSettingsController::class, 'edit'])
            ->name('admin.settings.general');

        Route::put('/settings/general', [AdminSiteSettingsController::class, 'update'])
            ->name('admin.settings.general.update');

        Route::get('/integrations', [AdminIntegrationSettingsController::class, 'edit'])
            ->name('admin.integrations.settings');

        Route::put('/integrations', [AdminIntegrationSettingsController::class, 'update'])
            ->name('admin.integrations.update');

        Route::post('/integrations/test-mail', [AdminIntegrationSettingsController::class, 'testMail'])
            ->name('admin.integrations.test-mail');

        Route::get('/integrations/keys', [AdminApiKeysController::class, 'edit'])
            ->name('admin.integrations.keys');

        Route::put('/integrations/keys', [AdminApiKeysController::class, 'update'])
            ->name('admin.integrations.keys.update');

        foreach (
            [
                'users',
                'products',
                'orders',
                'finance',
                'subscriptions',
                'assets',
                'reader',
                'loyalty',
                'content',
                'marketing',
                'ai',
                'support',
                'notifications',
                'reports',
                'storage',
                'search',
                'sellers',
                'workflow',
                'tasks',
                'security',
                'integrations',
                'settings',
                'system',
                'developer',
                'newsletter',
                'sms',
            ] as $module
        ) {
            Route::get('/' . $module . '/overview', [AdminPanelController::class, 'module'])
                ->defaults('module', $module)
                ->name('admin.module.' . $module);

            Route::get('/' . $module . '/{sub}', [AdminPanelController::class, 'module'])
                ->defaults('module', $module)
                ->name('admin.module.' . $module . '.sub');
        }
    });