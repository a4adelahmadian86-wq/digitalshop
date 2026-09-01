<?php

namespace App\Services\Storage;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LocalStorageProvider implements StorageProviderInterface
{
    protected string $disk;

    public function __construct(
        string $disk = 'local'
    ) {
        $this->disk = $disk;
    }

    public function put(
        UploadedFile $file,
        string $path
    ): string {
        $stored = Storage::disk($this->disk)
            ->putFileAs(
                dirname($path),
                $file,
                basename($path)
            );

        if (!$stored) {
            throw new \RuntimeException(
                'ذخیره فایل در Local Storage انجام نشد.'
            );
        }

        return $stored;
    }

    public function delete(
        string $path
    ): bool {
        if (!$this->exists($path)) {
            return true;
        }

        return Storage::disk($this->disk)
            ->delete($path);
    }

    public function exists(
        string $path
    ): bool {
        return Storage::disk($this->disk)
            ->exists($path);
    }

    public function download(
        string $path,
        ?string $name = null
    ): StreamedResponse {
        abort_unless(
            $this->exists($path),
            404
        );

        return Storage::disk($this->disk)
            ->download(
                $path,
                $name
            );
    }

    public function testConnection(): bool
    {
        $testPath =
            'storage-test/' .
            uniqid('test_', true) .
            '.txt';

        $disk = Storage::disk($this->disk);

        $disk->put(
            $testPath,
            'DigitalShop Storage Test'
        );

        $exists = $disk->exists($testPath);

        $disk->delete($testPath);

        return $exists;
    }
}