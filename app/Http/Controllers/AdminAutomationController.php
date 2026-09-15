<?php

namespace App\Http\Controllers;

use App\Models\Automation;
use App\Models\AutomationRun;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class AdminAutomationController extends Controller
{
    public function index(Request $request)
    {
        $automations = Automation::query()->orderBy('name')->get();
        $status = $request->string('status')->toString();
        $automationId = $request->integer('automation_id');

        $runs = AutomationRun::with(['automation', 'actor'])
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->when($automationId, fn ($query) => $query->where('automation_id', $automationId))
            ->latest('started_at')
            ->paginate(25)
            ->withQueryString();

        return view('admin.automation.index', compact('automations', 'runs', 'status', 'automationId'));
    }

    public function update(Request $request, Automation $automation, AuditLogger $auditLogger)
    {
        $data = $request->validate([
            'is_enabled' => ['required', 'boolean'],
            'schedule' => ['required', 'string', 'max:100', 'regex:/^(dailyAt:[0-2][0-9]:[0-5][0-9]|hourly|everyFiveMinutes|everyTenMinutes|everyThirtyMinutes)$/'],
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

        try {
            $exitCode = Artisan::call('digitalshop:automation', [
                'key' => $automation->key,
                '--triggered-by' => (string) $request->user()->id,
            ]);
            $output = trim(Artisan::output());
            $status = $exitCode === 0 ? 'success' : 'failed';
        } catch (\Throwable $e) {
            $exitCode = 1;
            $output = $e->getMessage();
            $status = 'failed';
        }

        $run = AutomationRun::where('automation_id', $automation->id)
            ->where('triggered_by', $request->user()->id)
            ->latest('started_at')
            ->first();

        $auditLogger->record($request, 'automation.run', $automation, [
            'run_id' => $run?->id,
            'status' => $status,
            'exit_code' => $exitCode,
        ], $status);

        return back()->with($status === 'success' ? 'success' : 'error', $status === 'success'
            ? 'اتوماسیون با موفقیت اجرا شد.'
            : 'اجرای اتوماسیون با خطا پایان یافت: ' . Str::limit($output, 220));
    }
}
