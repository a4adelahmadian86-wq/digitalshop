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
            try { return Crypt::decryptString($config['api_key_encrypted']); }
            catch (\Throwable $e) { throw new \RuntimeException('API Key ذخیره‌شده قابل رمزگشایی نیست.'); }
        }
        return (string) ($config['api_key'] ?? '');
    }

    public function providersFor(string $scope, string $location = 'external')
    {
        return StorageProvider::query()
            ->where('scope', $scope)->where('location', $location)->where('is_active', true)
            ->orderBy('priority')->orderBy('id')->get();
    }

    public function select(string $scope, string $location = 'external', ?int $preferredId = null, int $requiredBytes = 0): ?StorageProvider
    {
        $providers = $this->providersFor($scope, $location);
        if ($preferredId) {
            $preferred = $providers->firstWhere('id', $preferredId);
            if ($preferred && $this->hasCapacity($preferred, $requiredBytes)) return $preferred;
        }
        foreach ($providers as $provider) {
            if ($this->hasCapacity($provider, $requiredBytes)) return $provider;
        }
        return null;
    }

    public function for(string $scope, string $location = 'external'): StorageProvider
    {
        return $this->select($scope, $location)
            ?? throw new \RuntimeException("برای مسیر {$scope}/{$location} Storage فعال و دارای فضای کافی وجود ندارد.");
    }

    public function defaultProvider(): StorageProvider
    {
        return StorageProvider::query()->where('is_active', true)->where('is_default', true)->orderBy('priority')->firstOrFail();
    }

    public function upload(StorageProvider $provider, UploadedFile $file, string $path): string
    {
        $size = (int) $file->getSize();
        $this->assertLimits($provider, $size);
        $stored = $this->provider($provider)->put($file, $path);
        $this->refreshUsage($provider);
        return $stored;
    }

    public function uploadTo(string $scope, UploadedFile $file, string $path, string $location = 'external', ?int $preferredId = null): array
    {
        $size = (int) $file->getSize();
        $providers = $this->providersFor($scope, $location);
        $ordered = $providers->sortBy(function ($provider) use ($preferredId) {
            return [$preferredId && $provider->id === $preferredId ? 0 : 1, $provider->priority, $provider->id];
        });
        $errors = [];
        foreach ($ordered as $provider) {
            if (!$this->hasCapacity($provider, $size)) continue;
            try {
                return ['provider' => $provider, 'path' => $this->upload($provider, $file, $path)];
            } catch (\Throwable $e) {
                $errors[] = $provider->name . ': ' . $e->getMessage();
            }
        }
        throw new \RuntimeException('هیچ Storage Provider قابل استفاده‌ای برای آپلود پیدا نشد.' . ($errors ? ' ' . implode(' | ', $errors) : ''));
    }

    public function delete(StorageProvider $provider, string $path): bool
    {
        $result = $this->provider($provider)->delete($path);
        $this->refreshUsage($provider);
        return $result;
    }

    public function refreshUsage(StorageProvider $provider): array
    {
        $usage = $this->provider($provider)->usage();
        $config = $provider->config ?? [];
        $config['used_bytes'] = (int) ($usage['bytes'] ?? 0);
        $config['used_files'] = (int) ($usage['files'] ?? 0);
        $config['usage_checked_at'] = now()->toIso8601String();
        $provider->forceFill(['config' => $config])->saveQuietly();
        return $usage;
    }

    public function hasCapacity(StorageProvider $provider, int $requiredBytes = 0): bool
    {
        $config = $provider->config ?? [];
        $maxFile = (int) ($config['max_file_bytes'] ?? 0);
        $limit = (int) ($config['limit_bytes'] ?? 0);
        $used = (int) ($config['used_bytes'] ?? 0);
        $maxFiles = (int) ($config['max_files'] ?? 0);
        $usedFiles = (int) ($config['used_files'] ?? 0);
        if ($requiredBytes > 0 && $maxFile > 0 && $requiredBytes > $maxFile) return false;
        if ($limit > 0 && $used + $requiredBytes > $limit) return false;
        if ($maxFiles > 0 && $usedFiles >= $maxFiles) return false;
        return true;
    }

    protected function assertLimits(StorageProvider $provider, int $size): void
    {
        if (!$this->hasCapacity($provider, $size)) throw new \RuntimeException('فضای آزاد یا محدودیت فایل‌های Provider برای این فایل کافی نیست.');
    }
}
