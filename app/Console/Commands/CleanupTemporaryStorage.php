<?php

namespace App\Console\Commands;

use App\Services\Storage\StorageManager;
use Illuminate\Console\Command;

class CleanupTemporaryStorage extends Command
{
    protected $signature = 'digitalshop:cleanup-temporary';

    protected $description = 'پاکسازی فایل‌های موقت بر اساس retention_days هر Provider';

    public function handle(StorageManager $storage): int
    {
        $deleted = $storage->cleanupTemporary();
        $this->info("Deleted temporary files: {$deleted}");

        return self::SUCCESS;
    }
}
