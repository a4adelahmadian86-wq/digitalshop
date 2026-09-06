<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Services\Storage\StorageManager;
use App\Services\Sms\KPanelSmsProvider;
use App\Services\Sms\SmsProviderInterface;
use App\Services\Sms\TestSmsProvider;
use App\Models\Order;
use App\Models\Product;
use App\Observers\OrderObserver;
use App\Observers\ProductAiObserver;
use App\Services\AI\AIManager;
use App\Services\AI\AIProviderInterface;
use App\Services\AI\NullAIProvider;
use App\Services\AI\OpenAICompatibleProvider;
use App\Services\AI\GeminiProvider;
use App\Services\AI\GapGptProvider;
use App\Services\AI\ResilientAIProvider;
use App\Services\AI\AiRuntimeLogger;
use App\Services\AI\AiSettingsStore;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(StorageManager::class, fn () => new StorageManager());

        $this->app->bind(
            SmsProviderInterface::class,
            fn () => config('services.sms_provider') === 'kpanel'
                ? new KPanelSmsProvider()
                : new TestSmsProvider()
        );

        $this->app->singleton(AIProviderInterface::class, function () {
            $settings = app(AiSettingsStore::class);
            $primary = $settings->get('key', config('ai.key'))
                ? new GeminiProvider()
                : new NullAIProvider();
            $fallback = $settings->get('fallback_key', config('ai.fallback_key'))
                ? new GapGptProvider()
                : new NullAIProvider();

            return new ResilientAIProvider($primary, $fallback);
        });

        $this->app->singleton(
            AIManager::class,
            fn ($app) => new AIManager(
                $app->make(AIProviderInterface::class),
                $app->make(AiRuntimeLogger::class)
            )
        );
    }

    public function boot(): void
    {
        Order::observe(OrderObserver::class);
        Product::observe(ProductAiObserver::class);

        Route::middleware('web')
            ->get('/Images/{path}', [\App\Http\Controllers\MediaController::class, 'legacy'])
            ->where('path', '.*')
            ->name('images.legacy');

        // The AI settings page is already exposed as GET/PUT in web.php.
        // Keep a POST endpoint here as a compatibility route for the existing admin form.
        Route::middleware(['web', 'auth', 'role:admin'])
            ->post('/admin/ai/settings', [\App\Http\Controllers\AdminAiSettingsController::class, 'update'])
            ->name('admin.ai.settings.update');
    }
}
