<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
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
use App\Services\AI\AiRuntimeLogger;
class AppServiceProvider extends ServiceProvider
{
 public function register():void{
  $this->app->singleton(StorageManager::class,fn()=>new StorageManager());
  $this->app->bind(SmsProviderInterface::class,fn()=>config('services.sms_provider')==='kpanel'?new KPanelSmsProvider():new TestSmsProvider());
  $this->app->singleton(AIProviderInterface::class,function(){if(config('ai.provider')==='gemini'&&config('ai.key'))return new GeminiProvider();if(config('ai.provider')!=='null'&&config('ai.endpoint')&&config('ai.key'))return new OpenAICompatibleProvider();return new NullAIProvider();});
  $this->app->singleton(AIManager::class,fn($app)=>new AIManager($app->make(AIProviderInterface::class),$app->make(AiRuntimeLogger::class)));
 }
 public function boot():void{Order::observe(OrderObserver::class);Product::observe(ProductAiObserver::class);}
}