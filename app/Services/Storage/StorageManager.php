<?php

namespace App\Services\Storage;

use App\Models\StorageProvider;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Crypt;

class StorageManager
{
    public function provider(StorageProvider $provider): StorageProviderInterface
    {
        $config = $provider->config ?? [];

        return match ($provider->type) {
            'local' => new LocalStorageProvider($config['disk'] ?? 'local'),
            'api' => new ApiStorageProvider(
                $config['endpoint'] ?? throw new \RuntimeException('API Storage endpoint تنظیم نشده است.'),
                $this->apiKey($config),
                (int) ($config['timeout'] ?? 120),
                $config
            ),
            default => throw new \RuntimeException("Storage provider type [{$provider->type}] پشتیبانی نمی‌شود."),
        };
    }

    protected function apiKey(array $config): string
    {
        if (!empty($config['api_key_encrypted'])) {
            try { return Crypt::decryptString($config['api_key_encrypted']); } catch (\Throwable $e) { throw new \RuntimeException('API Key ذخیره‌شده قابل رمزگشایی نیست.'); }
        }
        return (string) ($config['api_key'] ?? '');
    }

    public function defaultProvider(): StorageProvider
    {
        return StorageProvider::query()->where('is_active', true)->where('is_default', true)->firstOrFail();
    }

    public function upload(StorageProvider $provider, UploadedFile $file, string $path): string
    {
        return $this->provider($provider)->put($file, $path);
    }
}