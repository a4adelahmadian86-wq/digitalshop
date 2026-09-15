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
            $definition = $automation->schedule;
            [$type, $time] = array_pad(explode(':', $definition, 2), 2, null);

            $event = match ($type) {
                'hourly' => $schedule->command($automation->command),
                'everyFiveMinutes' => $schedule->command($automation->command)->everyFiveMinutes(),
                'everyTenMinutes' => $schedule->command($automation->command)->everyTenMinutes(),
                'everyThirtyMinutes' => $schedule->command($automation->command)->everyThirtyMinutes(),
                'dailyAt' => $schedule->command($automation->command)->dailyAt($time ?: '10:00'),
                default => null,
            };

            if ($event && $type === 'hourly') {
                $event->hourly();
            }
        });
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
