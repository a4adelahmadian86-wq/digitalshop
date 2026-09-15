@extends('dashboard.layout')

@section('title', 'مرکز اتوماسیون')

@section('content')
<div class="fd-head">
    <div>
        <div class="fd-eyebrow">AUTOMATION CENTER</div>
        <h1>مرکز اتوماسیون</h1>
        <p>مدیریت زمان‌بندی، اجرای دستی، وضعیت و تاریخچه فرآیندهای خودکار.</p>
    </div>
</div>

@if(session('success'))<div class="fd-alert fd-success" role="status">{{ session('success') }}</div>@endif
@if(session('error'))<div class="fd-alert fd-danger" role="alert">{{ session('error') }}</div>@endif
@if($errors->any())<div class="fd-alert fd-danger" role="alert">{{ $errors->first() }}</div>@endif

<div class="fd-grid">
    <div class="fd-stat"><small>اتوماسیون‌ها</small><strong>{{ number_format($automations->count()) }}</strong><span>{{ number_format($automations->where('is_enabled', true)->count()) }} فعال</span></div>
    <div class="fd-stat"><small>اجراهای نمایش‌داده‌شده</small><strong>{{ number_format($runs->total()) }}</strong><span>با فیلتر و صفحه‌بندی</span></div>
    <div class="fd-stat"><small>آخرین وضعیت</small><strong>{{ $runs->first()?->status === 'success' ? 'موفق' : ($runs->first() ? 'بررسی' : '—') }}</strong><span>{{ $runs->first()?->started_at?->format('Y/m/d H:i') ?? 'هنوز اجرا نشده' }}</span></div>
    <div class="fd-stat"><small>Scheduler</small><strong>فعال</strong><span>با اجرای schedule:run هر دقیقه</span></div>
</div>

@foreach($automations as $automation)
<section class="fd-card" style="margin-top:16px">
    <div class="fd-card-head">
        <div><small>{{ $automation->key }}</small><h2>{{ $automation->name }}</h2></div>
        <span class="fd-badge {{ $automation->is_enabled ? 'on' : 'off' }}">{{ $automation->is_enabled ? 'فعال' : 'غیرفعال' }}</span>
    </div>
    <p style="font-size:11px;color:var(--fd-muted);margin:0 0 16px">{{ $automation->description }}</p>
    <div class="fd-automation-meta">
        <div><small>Command</small><code>{{ $automation->command }}</code></div>
        <div><small>Schedule</small><strong>{{ $automation->schedule }}</strong></div>
        <div><small>آخرین اجرا</small><strong>{{ $automation->last_run_at?->format('Y/m/d H:i:s') ?? '—' }}</strong></div>
        <div><small>آخرین خروجی</small><span>{{ $automation->last_output ?: '—' }}</span></div>
    </div>
    @if(auth()->user()->hasPermission('automation.manage'))
    <div class="fd-automation-actions">
        <form method="POST" action="{{ route('admin.automation.update', $automation) }}">
            @csrf @method('PUT')
            <input type="hidden" name="is_enabled" value="0">
            <label><input type="checkbox" name="is_enabled" value="1" @checked($automation->is_enabled)> فعال</label>
            <select name="schedule" aria-label="زمان‌بندی">
                @foreach(['dailyAt:10:00'=>'هر روز ساعت ۱۰:۰۰','hourly'=>'هر ساعت','everyFiveMinutes'=>'هر ۵ دقیقه','everyTenMinutes'=>'هر ۱۰ دقیقه','everyThirtyMinutes'=>'هر ۳۰ دقیقه'] as $value=>$label)
                    <option value="{{ $value }}" @selected($automation->schedule === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <button class="fd-btn primary" type="submit">ذخیره</button>
        </form>
        <form method="POST" action="{{ route('admin.automation.run', $automation) }}" onsubmit="return confirm('اتوماسیون واقعاً اجرا شود؟')">
            @csrf<button class="fd-btn" type="submit">اجرای دستی</button>
        </form>
    </div>
    @endif
</section>
@endforeach

<section class="fd-card" style="margin-top:16px">
    <div class="fd-card-head"><div><small>HISTORY</small><h2>تاریخچه اجرا</h2></div></div>
    <form method="GET" class="fd-filter">
        <select name="automation_id" aria-label="فیلتر اتوماسیون">
            <option value="">همه اتوماسیون‌ها</option>
            @foreach($automations as $automation)<option value="{{ $automation->id }}" @selected($automationId == $automation->id)>{{ $automation->name }}</option>@endforeach
        </select>
        <select name="status" aria-label="فیلتر وضعیت">
            <option value="">همه وضعیت‌ها</option>
            @foreach(['success'=>'موفق','failed'=>'ناموفق','running'=>'در حال اجرا'] as $value=>$label)<option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>@endforeach
        </select>
        <button class="fd-btn primary" type="submit">اعمال فیلتر</button>
        <a class="fd-btn" href="{{ route('admin.automation.index') }}">پاک کردن</a>
    </form>
    <div style="overflow:auto"><table class="fd-table"><thead><tr><th>اتوماسیون</th><th>شروع</th><th>پایان</th><th>عامل</th><th>وضعیت</th><th>کد</th><th>خروجی</th></tr></thead><tbody>
    @forelse($runs as $run)
        <tr><td>{{ $run->automation?->name }}</td><td>{{ $run->started_at?->format('Y/m/d H:i:s') }}</td><td>{{ $run->finished_at?->format('Y/m/d H:i:s') ?? '—' }}</td><td>{{ trim(($run->actor?->first_name ?? '') . ' ' . ($run->actor?->last_name ?? '')) ?: 'Scheduler' }}</td><td>{{ $run->status }}</td><td>{{ $run->exit_code ?? '—' }}</td><td title="{{ $run->output }}">{{ \Illuminate\Support\Str::limit($run->output, 120) }}</td></tr>
    @empty<tr><td colspan="7" class="fd-empty">هنوز اجرایی ثبت نشده است.</td></tr>@endforelse
    </tbody></table></div>
    <div class="fd-pagination">{{ $runs->links() }}</div>
</section>

<style>
.fd-badge{padding:5px 10px;border-radius:20px;font-size:10px;font-weight:800}.fd-badge.on{background:#ecfdf3;color:#027a48}.fd-badge.off{background:#f2f4f7;color:#667085}.fd-automation-meta{display:grid;grid-template-columns:1fr 1fr;gap:10px}.fd-automation-meta>div{border:1px solid var(--fd-border);border-radius:10px;padding:10px}.fd-automation-meta small{display:block;color:var(--fd-muted);font-size:9px;margin-bottom:5px}.fd-automation-meta strong,.fd-automation-meta span,.fd-automation-meta code{font-size:10px;word-break:break-word}.fd-automation-actions{display:flex;justify-content:space-between;gap:10px;align-items:center;margin-top:14px;padding-top:14px;border-top:1px solid var(--fd-border)}.fd-automation-actions form,.fd-filter{display:flex;gap:8px;align-items:center}.fd-automation-actions select,.fd-filter select{min-height:40px;border:1px solid var(--fd-border);border-radius:10px;padding:0 10px;background:#fff}.fd-filter{margin:0 0 14px;padding:12px;background:#f8f9fb;border:1px solid var(--fd-border);border-radius:10px}.fd-pagination{margin-top:14px;font-size:11px}@media(max-width:700px){.fd-automation-meta{grid-template-columns:1fr}.fd-automation-actions,.fd-filter{align-items:stretch;flex-direction:column}.fd-automation-actions form,.fd-filter{width:100%;flex-wrap:wrap}.fd-automation-actions select,.fd-automation-actions .fd-btn,.fd-filter select,.fd-filter .fd-btn{flex:1}}
</style>
@endsection