<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Services\Storage\StorageManager;
use App\Services\Sms\KPanelSmsProvider;
use App\Services\Sms\SmsProviderInterface;
use App\Services\Sms\TestSmsProvider;
use App\Models\Order;
use App\Observers\OrderObserver;
use App\Http\Middleware\InjectAdminAiAssets;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            StorageManager::class,
            function () {
                return new StorageManager();
            }
        );

        $this->app->bind(
            SmsProviderInterface::class,
            function () {
                return match (
                    config('services.sms_provider')
                ) {
                    'kpanel' => new KPanelSmsProvider(),
                    default => new TestSmsProvider(),
                };
            }
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Order::observe(OrderObserver::class);

        Route::matched(function ($event) {
            $route = $event->route;

            if (str_starts_with('/' . ltrim($route->uri(), '/'), '/admin')) {
                $route->middleware(InjectAdminAiAssets::class);
            }
        });
    }
}
