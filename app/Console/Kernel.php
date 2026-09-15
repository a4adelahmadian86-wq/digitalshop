<?php

namespace App\Console;

use App\Models\Automation;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        Automation::query()->where('is_enabled', true)->get()->each(function (Automation $automation) use ($schedule) {
            $event = match (true) {
                $automation->schedule === 'hourly' => $schedule->command('digitalshop:automation', [$automation->key])->hourly(),
                $automation->schedule === 'everyFiveMinutes' => $schedule->command('digitalshop:automation', [$automation->key])->everyFiveMinutes(),
                $automation->schedule === 'everyTenMinutes' => $schedule->command('digitalshop:automation', [$automation->key])->everyTenMinutes(),
                $automation->schedule === 'everyThirtyMinutes' => $schedule->command('digitalshop:automation', [$automation->key])->everyThirtyMinutes(),
                str_starts_with($automation->schedule, 'dailyAt:') => $schedule->command('digitalshop:automation', [$automation->key])->dailyAt(substr($automation->schedule, 9)),
                default => null,
            };

            if ($event) $event->withoutOverlapping();
        });
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
