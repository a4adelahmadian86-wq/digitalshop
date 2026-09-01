<?php

namespace App\Services\Storage;

use App\Models\StorageProvider;
use Illuminate\Http\UploadedFile;

class StorageManager
{
    public function provider(
        StorageProvider $provider
    ): StorageProviderInterface {

        return match ($provider->type) {

            'local' =>
                new LocalStorageProvider(
                    $provider->config['disk'] ?? 'local'
                ),

            'api' =>
                new ApiStorageProvider(
                    $provider->config['endpoint']
                        ?? throw new \RuntimeException(
                            'API Storage endpoint تنظیم نشده است.'
                        ),

                    $provider->config['api_key']
                        ?? throw new \RuntimeException(
                            'API Storage API Key تنظیم نشده است.'
                        ),

                    (int) (
                        $provider->config['timeout']
                        ?? 120
                    )
                ),

            default =>
                throw new \RuntimeException(
                    "Storage provider type [{$provider->type}] پشتیبانی نمی‌شود."
                ),

        };
    }

    public function defaultProvider(): StorageProvider
    {
        return StorageProvider::query()
            ->where('is_active', true)
            ->where('is_default', true)
            ->firstOrFail();
    }

    public function upload(
        StorageProvider $provider,
        UploadedFile $file,
        string $path
    ): string {
        return $this
            ->provider($provider)
            ->put(
                $file,
                $path
            );
    }
}