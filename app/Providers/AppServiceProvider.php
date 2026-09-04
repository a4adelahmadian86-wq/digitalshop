<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use App\Services\Storage\StorageManager;
use App\Services\Sms\KPanelSmsProvider;
use App\Services\Sms\SmsProviderInterface;
use App\Services\Sms\TestSmsProvider;
use App\Models\Order;use App\Models\Product;
use App\Observers\OrderObserver;use App\Observers\ProductAiObserver;
use App\Services\AI\AIManager;use App\Services\AI\AIProviderInterface;use App\Services\AI\NullAIProvider;use App\Services\AI\OpenAICompatibleProvider;use App\Services\AI\GeminiProvider;use App\Services\AI\GapGptProvider;use App\Services\AI\ResilientAIProvider;use App\Services\AI\AiRuntimeLogger;
class AppServiceProvider extends ServiceProvider{
 public function register():void{$this->app->singleton(StorageManager::class,fn()=>new StorageManager());$this->app->bind(SmsProviderInterface::class,fn()=>config('services.sms_provider')==='kpanel'?new KPanelSmsProvider():new TestSmsProvider());$this->app->singleton(AIProviderInterface::class,function(){$primary=config('ai.provider')==='gemini'&&config('ai.key')?new GeminiProvider():(config('ai.provider')!=='null'&&config('ai.endpoint')&&config('ai.key')?new OpenAICompatibleProvider():new NullAIProvider());$fallback=config('ai.fallback_provider')==='gapgpt'&&config('ai.fallback_key')&&config('ai.fallback_endpoint')?new GapGptProvider():new NullAIProvider();return new ResilientAIProvider($primary,$fallback);});$this->app->singleton(AIManager::class,fn($app)=>new AIManager($app->make(AIProviderInterface::class),$app->make(AiRuntimeLogger::class)));}
 public function boot():void{Order::observe(OrderObserver::class);Product::observe(ProductAiObserver::class);}
}