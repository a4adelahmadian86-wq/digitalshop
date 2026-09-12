<?php

namespace App\Services\Storage;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LocalStorageProvider implements StorageProviderInterface
{
    protected string $disk;

    public function __construct(string $disk = 'local')
    {
        $this->disk = $disk;
    }

    public function put(UploadedFile $file, string $path): string
    {
        $stored = Storage::disk($this->disk)->putFileAs(dirname($path), $file, basename($path));
        if (!$stored) {
            throw new \RuntimeException('ذخیره فایل در Local Storage انجام نشد.');
        }

        return $stored;
    }

    public function delete(string $path): bool
    {
        return !$this->exists($path) || Storage::disk($this->disk)->delete($path);
    }

    public function exists(string $path): bool
    {
        return Storage::disk($this->disk)->exists($path);
    }

    public function download(string $path, ?string $name = null): StreamedResponse
    {
        abort_unless($this->exists($path), 404);

        return Storage::disk($this->disk)->download($path, $name);
    }

    public function testConnection(): bool
    {
        $p = 'storage-test/'.uniqid('test_', true).'.txt';
        $d = Storage::disk($this->disk);
        $d->put($p, 'DigitalShop Storage Test');
        $ok = $d->exists($p);
        $d->delete($p);

        return $ok;
    }

    public function usage(): array
    {
        $d = Storage::disk($this->disk);
        $files = 0;
        $bytes = 0;
        foreach ($d->allFiles() as $f) {
            try {
                $files++;
                $bytes += (int) $d->size($f);
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return ['bytes' => $bytes, 'files' => $files];
    }

    public function cleanupOlderThan(int $seconds): int
    {
        $d = Storage::disk($this->disk);
        $cutoff = time() - max(1, $seconds);
        $deleted = 0;
        foreach ($d->allFiles('temporary') as $f) {
            try {
                if ($d->lastModified($f) < $cutoff && $d->delete($f)) {
                    $deleted++;
                }
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return $deleted;
    }
}
