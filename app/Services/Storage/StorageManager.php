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
        if (! empty($config['api_key_encrypted'])) {
            try {
                return Crypt::decryptString($config['api_key_encrypted']);
            } catch (\Throwable $e) {
                throw new \RuntimeException('API Key ذخیره‌شده قابل رمزگشایی نیست.');
            }
        }

        return (string) ($config['api_key'] ?? '');
    }

    public function providersFor(string $scope, string $location = 'external')
    {
        return StorageProvider::query()
            ->where('scope', $scope)
            ->where('location', $location)
            ->where('is_active', true)
            ->orderBy('priority')
            ->orderBy('id')
            ->get();
    }

    public function select(string $scope, string $location = 'external', ?int $preferredId = null, int $requiredBytes = 0): ?StorageProvider
    {
        $providers = $this->providersFor($scope, $location);
        if ($preferredId) {
            $p = $providers->firstWhere('id', $preferredId);
            if ($p && $this->hasCapacity($p, $requiredBytes)) {
                return $p;
            }
        }
        foreach ($providers as $p) {
            if ($this->hasCapacity($p, $requiredBytes)) {
                return $p;
            }
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
        return StorageProvider::query()
            ->where('is_active', true)
            ->where('is_default', true)
            ->orderBy('priority')
            ->firstOrFail();
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
        $ordered = $providers->sortBy(fn ($p) => [
            $preferredId && $p->id === $preferredId ? 0 : 1,
            $p->priority,
            $p->id,
        ]);
        $errors = [];
        foreach ($ordered as $p) {
            if (! $this->hasCapacity($p, $size)) {
                continue;
            }
            try {
                return ['provider' => $p, 'path' => $this->upload($p, $file, $path)];
            } catch (\Throwable $e) {
                $errors[] = $p->name.': '.$e->getMessage();
            }
        }

        throw new \RuntimeException('هیچ Storage Provider قابل استفاده‌ای برای آپلود پیدا نشد.'.($errors ? ' '.implode(' | ', $errors) : ''));
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

    public function cleanupTemporary(): int
    {
        $deleted = 0;
        foreach ($this->providersFor('temporary', 'internal')->merge($this->providersFor('temporary', 'external')) as $provider) {
            $days = max(1, (int) (($provider->config ?? [])['retention_days'] ?? 7));
            $deleted += $this->provider($provider)->cleanupOlderThan($days * 86400);
            $this->refreshUsage($provider);
        }

        return $deleted;
    }

    public function hasCapacity(StorageProvider $provider, int $requiredBytes = 0): bool
    {
        $c = $provider->config ?? [];
        $max = (int) ($c['max_file_bytes'] ?? 0);
        $limit = (int) ($c['limit_bytes'] ?? 0);
        $used = (int) ($c['used_bytes'] ?? 0);
        $maxFiles = (int) ($c['max_files'] ?? 0);
        $usedFiles = (int) ($c['used_files'] ?? 0);
        if ($requiredBytes > 0 && $max > 0 && $requiredBytes > $max) {
            return false;
        }
        if ($limit > 0 && $used + $requiredBytes > $limit) {
            return false;
        }
        if ($maxFiles > 0 && $usedFiles >= $maxFiles) {
            return false;
        }

        return true;
    }

    protected function assertLimits(StorageProvider $provider, int $size): void
    {
        if (! $this->hasCapacity($provider, $size)) {
            throw new \RuntimeException('فضای آزاد یا محدودیت فایل‌های Provider برای این فایل کافی نیست.');
        }
    }
}
