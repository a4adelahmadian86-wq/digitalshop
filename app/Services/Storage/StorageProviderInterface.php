<?php

namespace App\Services\Storage;

use Illuminate\Http\UploadedFile;

interface StorageProviderInterface
{
    public function put(
        UploadedFile $file,
        string $path
    ): string;

    public function delete(
        string $path
    ): bool;

    public function exists(
        string $path
    ): bool;

    public function download(
        string $path,
        ?string $name = null
    );

    public function testConnection(): bool;
}