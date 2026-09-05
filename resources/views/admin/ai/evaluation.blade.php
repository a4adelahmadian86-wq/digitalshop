@extends('admin.layout')

@section('title','ارزیابی مدل‌های هوش مصنوعی')

@section('content')
<div class="admin-module-page">
    <div class="account-head">
        <div><small>مدیریت سامانه / هوش مصنوعی</small><h1>ارزیابی مدل‌ها</h1><p>اجرای ارزیابی روی محصولات واقعی و مشاهده نتیجه‌های ثبت‌شده.</p></div>
    </div>

    @if(session('success'))<div class="alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert-error">{{ session('error') }}</div>@endif

    <section class="panel-section eval-summary">
        <div><span>Provider فعال</span><strong>{{ $provider ?: 'تنظیم نشده' }}</strong></div>
        <div><span>مدل‌های ارزیابی</span><strong>{{ count($models) }}</strong></div>
        <div><span>آزمایش‌های ثبت‌شده</span><strong>{{ $experiments->count() }}</strong></div>
    </section>

    <section class="panel-section">
        <div class="module-section-title"><div><h2>اجرای ارزیابی</h2><span>فقط با تنظیم Provider و حداقل یک محصول اجرا می‌شود.</span></div></div>
        <form method="POST" action="{{ route('admin.ai.evaluation.run') }}">@csrf<button class="btn primary" type="submit">اجرای ارزیابی مدل‌ها</button></form>
    </section>

    <section class="panel-section">
        <div class="module-section-title"><div><h2>مدل‌های تعریف‌شده</h2><span>پیکربندی فعلی</span></div></div>
        @if(count($models))
            <div class="eval-models">@foreach($models as $model)<div class="eval-card"><strong>{{ is_array($model) ? ($model['name'] ?? $model['model'] ?? 'مدل') : $model }}</strong><small>{{ is_array($model) ? json_encode($model, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) : 'مدل ارزیابی' }}</small></div>@endforeach</div>
        @else
            <div class="module-empty"><strong>هنوز مدلی برای ارزیابی تنظیم نشده است.</strong><span>ابتدا Provider و مدل‌های AI را در تنظیمات سامانه مشخص کنید.</span></div>
        @endif
    </section>

    <section class="panel-section">
        <div class="module-section-title"><div><h2>سوابق ارزیابی</h2><span>{{ $experiments->count() }} رکورد</span></div></div>
        @if($experiments->isNotEmpty())
            <div class="table-wrap"><table class="panel-table"><thead><tr><th>#</th><th>مدل</th><th>وضعیت</th><th>امتیاز</th><th>تاریخ</th></tr></thead><tbody>@foreach($experiments as $experiment)<tr><td>{{ $experiment->id }}</td><td>{{ $experiment->model ?? $experiment->model_name ?? '—' }}</td><td>{{ $experiment->status ?? '—' }}</td><td>{{ $experiment->score ?? '—' }}</td><td>{{ optional($experiment->created_at)->format('Y/m/d H:i') }}</td></tr>@endforeach</tbody></table></div>
        @else
            <div class="module-empty"><strong>هنوز ارزیابی‌ای ثبت نشده است.</strong><span>پس از اجرای ارزیابی، نتایج واقعی اینجا نمایش داده می‌شوند.</span></div>
        @endif
    </section>
</div>
@endsection

@push('styles')
<style>
.eval-summary{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px}.eval-summary>div,.eval-card{padding:16px;border:1px solid #eaecf0;border-radius:15px;background:#fff}.eval-summary span,.eval-summary strong{display:block}.eval-summary span{font-size:10px;color:#98a2b3}.eval-summary strong{margin-top:7px;font-size:15px}.eval-models{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px}.eval-card{display:grid;gap:6px}.eval-card small{font-size:10px;color:#667085;word-break:break-word}@media(max-width:650px){.eval-summary,.eval-models{grid-template-columns:1fr}}
</style>
@endpush
