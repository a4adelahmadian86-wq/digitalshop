<?php

namespace App\Console\Commands;

use App\Models\Automation;
use App\Models\AutomationRun;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class RunAutomation extends Command
{
    protected $signature = 'digitalshop:automation {key} {--dry-run}';
    protected $description = 'Run a configured FARAST automation and persist its execution result.';

    public function handle(): int
    {
        $automation = Automation::where('key', $this->argument('key'))->first();
        if (!$automation) {
            $this->error('Automation not found.');
            return self::FAILURE;
        }

        if (!$automation->is_enabled) {
            $this->warn('Automation is disabled.');
            return self::SUCCESS;
        }

        if (!Str::startsWith($automation->command, 'digitalshop:') || $automation->command === 'digitalshop:automation') {
            $this->error('Automation command is not allowed.');
            return self::FAILURE;
        }

        $run = AutomationRun::create([
            'automation_id' => $automation->id,
            'started_at' => now(),
            'status' => 'running',
        ]);

        try {
            $args = [];
            if ($this->option('dry-run')) $args['--dry-run'] = true;
            $exitCode = Artisan::call($automation->command, $args);
            $output = trim(Artisan::output());
            $status = $exitCode === 0 ? 'success' : 'failed';
        } catch (\Throwable $e) {
            $exitCode = 1;
            $output = $e->getMessage();
            $status = 'failed';
        }

        $run->update([
            'finished_at' => now(),
            'exit_code' => $exitCode,
            'status' => $status,
            'output' => mb_substr($output, 0, 10000),
        ]);

        $automation->update([
            'last_run_at' => now(),
            'last_exit_code' => $exitCode,
            'last_output' => mb_substr($output, 0, 10000),
        ]);

        $status === 'success' ? $this->info($output ?: 'Automation completed.') : $this->error($output ?: 'Automation failed.');
        return $exitCode === 0 ? self::SUCCESS : self::FAILURE;
    }
}