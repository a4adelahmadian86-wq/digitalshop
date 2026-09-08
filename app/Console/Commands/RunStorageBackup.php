<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;

class RunStorageBackup extends Command
{
    protected $signature = 'digitalshop:backup';
    protected $description = 'ایجاد بکاپ پایگاه داده و ارسال آن به Storage داخلی و خارجی';

    public function handle(BackupService $backup): int
    {
        $items = $backup->run();
        if (!$items) {
            $this->error('هیچ Storage Provider فعالی برای بکاپ پیدا نشد.');
            return self::FAILURE;
        }
        foreach ($items as $item) $this->info("Backup: {$item['location']} / provider #{$item['provider_id']} / {$item['path']}");
        return self::SUCCESS;
    }
}
