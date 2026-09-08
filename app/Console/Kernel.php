<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('digitalshop:recommendations')->dailyAt('10:00');
        $schedule->command('digitalshop:storage-cleanup')->dailyAt('03:30');
        $schedule->command('digitalshop:backup')->weeklyOn(0,'02:30');
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
