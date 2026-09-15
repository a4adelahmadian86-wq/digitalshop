<?php

namespace App\Http\Controllers;

use App\Models\Automation;
use App\Models\AutomationRun;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminAutomationController extends Controller
{
    public function index()
    {
        $automations = Automation::query()->orderBy('name')->get();
        $runs = AutomationRun::with(['automation', 'actor'])->latest('started_at')->limit(20)->get();

        return view('admin.automation.index', compact('automations', 'runs'));
    }

    public function update(Request $request, Automation $automation, AuditLogger $auditLogger)
    {
        $data = $request->validate([
            'is_enabled' => ['required', 'boolean'],
            'schedule' => ['required', 'string', 'max:100', 'regex:/^(dailyAt|hourly|everyFiveMinutes|everyTenMinutes|everyThirtyMinutes):?([0-2][0-9]:[0-5][0-9])?$/'],
        ]);

        $automation->update($data);
        $auditLogger->record($request, 'automation.updated', $automation, $data);

        return back()->with('success', 'تنظیمات اتوماسیون ذخیره شد.');
    }

    public function run(Request $request, Automation $automation, AuditLogger $auditLogger)
    {
        abort_unless($automation->is_enabled, 422, 'این اتوماسیون غیرفعال است.');
        abort_unless(Str::startsWith($automation->command, 'digitalshop:'), 422, 'دستور اتوماسیون مجاز نیست.');
        abort_if($automation->command === 'digitalshop:automation', 422, 'اجرای بازگشتی اتوماسیون مجاز نیست.');

        $run = AutomationRun::create([
            'automation_id' => $automation->id,
            'triggered_by' => $request->user()->id,
            'started_at' => now(),
            'status' => 'running',
        ]);

        try {
            $exitCode = Artisan::call($automation->command);
            $output = trim(Artisan::output());
            $status = $exitCode === 0 ? 'success' : 'failed';
        } catch (\Throwable $e) {
            $exitCode = 1;
            $output = $e->getMessage();
            $status = 'failed';
        }

        DB::transaction(function () use ($run, $automation, $exitCode, $output, $status) {
            $run->update([
                'finished_at' => now(), 'exit_code' => $exitCode, 'status' => $status,
                'output' => mb_substr($output, 0, 10000),
            ]);
            $automation->update([
                'last_run_at' => now(), 'last_exit_code' => $exitCode,
                'last_output' => mb_substr($output, 0, 10000),
            ]);
        });

        $auditLogger->record($request, 'automation.run', $automation, [
            'run_id' => $run->id, 'status' => $status, 'exit_code' => $exitCode,
        ], $status);

        return back()->with($status === 'success' ? 'success' : 'error', $status === 'success' ? 'اتوماسیون با موفقیت اجرا شد.' : 'اجرای اتوماسیون با خطا پایان یافت.');
    }
}
