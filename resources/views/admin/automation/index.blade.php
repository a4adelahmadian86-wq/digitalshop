@extends('dashboard.layout')

@section('title', 'مرکز اتوماسیون')

@section('content')
<div class="fd-head">
    <div><div class="fd-eyebrow">AUTOMATION CENTER</div><h1>مرکز اتوماسیون</h1><p>فعال‌سازی، زمان‌بندی، اجرای آزمایشی و مشاهده سابقه اجرای فرآیندهای خودکار.</p></div>
</div>

@if(session('success'))<div class="fd-alert fd-success" role="status">{{ session('success') }}</div>@endif
@if(session('error'))<div class="fd-alert fd-danger" role="alert">{{ session('error') }}</div>@endif
@if($errors->any())<div class="fd-alert fd-danger" role="alert">{{ $errors->first() }}</div>@endif

<div class="fd-grid">
    <div class="fd-stat"><small>اتوماسیون‌ها</small><strong>{{ number_format($automations->count()) }}</strong><span>{{ number_format($automations->where('is_enabled', true)->count()) }} فعال</span></div>
    <div class="fd-stat"><small>اجراهای ثبت‌شده</small><strong>{{ number_format($runs->count()) }}</strong><span>۲۰ اجرای اخیر</span></div>
    <div class="fd-stat"><small>آخرین وضعیت</small><strong>{{ $runs->first()?->status === 'success' ? 'موفق' : ($runs->first() ? 'نیازمند بررسی' : '—') }}</strong><span>{{ $runs->first()?->started_at?->format('Y/m/d H:i') ?? 'هنوز اجرا نشده' }}</span></div>
    <div class="fd-stat"><small>زمان‌بندی</small><strong>Scheduler</strong><span>نیازمند اجرای cron در سرور</span></div>
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
        <div><small>خروجی آخر</small><span>{{ $automation->last_output ?: '—' }}</span></div>
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
        <form method="POST" action="{{ route('admin.automation.run', $automation) }}" onsubmit="return confirm('اجرای آزمایشی انجام شود؟')">
            @csrf<button class="fd-btn" type="submit">اجرای آزمایشی</button>
        </form>
    </div>
    @endif
</section>
@endforeach

<section class="fd-card" style="margin-top:16px">
    <div class="fd-card-head"><div><small>HISTORY</small><h2>تاریخچه اجرا</h2></div></div>
    <div style="overflow:auto"><table class="fd-table"><thead><tr><th>اتوماسیون</th><th>شروع</th><th>پایان</th><th>عامل</th><th>وضعیت</th><th>خروجی</th></tr></thead><tbody>
    @forelse($runs as $run)
        <tr><td>{{ $run->automation?->name }}</td><td>{{ $run->started_at?->format('Y/m/d H:i:s') }}</td><td>{{ $run->finished_at?->format('Y/m/d H:i:s') ?? '—' }}</td><td>{{ trim(($run->actor?->first_name ?? '') . ' ' . ($run->actor?->last_name ?? '')) ?: 'Scheduler' }}</td><td>{{ $run->status }}</td><td>{{ \Illuminate\Support\Str::limit($run->output, 120) }}</td></tr>
    @empty<tr><td colspan="6" class="fd-empty">هنوز اجرایی ثبت نشده است.</td></tr>@endforelse
    </tbody></table></div>
</section>

<style>
.fd-badge{padding:5px 10px;border-radius:20px;font-size:10px;font-weight:800}.fd-badge.on{background:#ecfdf3;color:#027a48}.fd-badge.off{background:#f2f4f7;color:#667085}.fd-automation-meta{display:grid;grid-template-columns:1fr 1fr;gap:10px}.fd-automation-meta>div{border:1px solid var(--fd-border);border-radius:10px;padding:10px}.fd-automation-meta small{display:block;color:var(--fd-muted);font-size:9px;margin-bottom:5px}.fd-automation-meta strong,.fd-automation-meta span,.fd-automation-meta code{font-size:10px;word-break:break-word}.fd-automation-actions{display:flex;justify-content:space-between;gap:10px;align-items:center;margin-top:14px;padding-top:14px;border-top:1px solid var(--fd-border)}.fd-automation-actions form{display:flex;gap:8px;align-items:center}.fd-automation-actions select{min-height:40px;border:1px solid var(--fd-border);border-radius:10px;padding:0 10px;background:#fff}@media(max-width:700px){.fd-automation-meta{grid-template-columns:1fr}.fd-automation-actions{align-items:stretch;flex-direction:column}.fd-automation-actions form{width:100%;flex-wrap:wrap}.fd-automation-actions select,.fd-automation-actions .fd-btn{flex:1}}
</style>
@endsection