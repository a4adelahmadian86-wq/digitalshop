<?php

namespace App\Console\Commands;

use App\Services\Storage\StorageManager;
use Illuminate\Console\Command;

class CleanupTemporaryStorage extends Command
{
    protected $signature = 'digitalshop:storage-cleanup';
    protected $description = 'پاکسازی فایل‌های موقت منقضی‌شده از Storageهای داخلی و خارجی';

    public function handle(StorageManager $storage): int
    {
        $deleted = $storage->cleanupTemporary();
        $this->info("Temporary files deleted: {$deleted}");
        return self::SUCCESS;
    }
}
