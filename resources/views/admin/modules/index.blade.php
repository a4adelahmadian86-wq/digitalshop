@extends('admin.layout')
@section('title',$moduleTitle.' | '.$sectionTitle)
@section('content')
<div class="admin-module-page">
    <div class="account-head">
        <div><small>مدیریت سامانه / {{ $moduleTitle }}</small><h1>{{ $sectionTitle }}</h1><p>این بخش به‌صورت عملیاتی به داده‌های فعلی سامانه متصل است؛ آمار و رکوردهای موجود بدون داده‌سازی نمایشی نمایش داده می‌شوند.</p></div>
    </div>
    <div class="module-tabs">
        @foreach($sections as $key=>$label)<a class="{{ $key===$section?'active':'' }}" href="{{ route('admin.module',['module'=>$module,'section'=>$key]) }}">{{ $label }}</a>@endforeach
    </div>
    <div class="panel-widgets">
        <div class="widget-card"><span class="widget-icon">داده</span><strong class="widget-value">{{ number_format($count) }}</strong><small>{{ $table ?: 'برای این بخش جدول مستقیمی ثبت نشده' }}</small></div>
        <div class="widget-card"><span class="widget-icon">بخش</span><strong class="widget-value">{{ number_format(count($sections)) }}</strong><small>بخش عملیاتی این ماژول</small></div>
        <div class="widget-card"><span class="widget-icon">وضعیت</span><strong class="widget-value">فعال</strong><small>مسیر مدیریت در دسترس است</small></div>
    </div>
    @if($quickRoutes)
    <section class="panel-section"><div class="module-section-title"><h2>دسترسی‌های اجرایی</h2><span>مسیرهای تخصصی ساخته‌شده</span></div><div class="module-actions">@foreach($quickRoutes as $label=>$routeName)<a class="btn primary" href="{{ route($routeName) }}">{{ $label }}</a>@endforeach</div></section>
    @endif
    <section class="panel-section">
        <div class="module-section-title"><h2>رکوردهای اخیر</h2><span>{{ $rows->count() }} رکورد</span></div>
        <form method="GET" class="admin-filter module-search"><input class="form-control" name="q" value="{{ $q }}" placeholder="جستجو در داده‌های قابل جستجو"><button class="btn primary" type="submit">جستجو</button>@if($q)<a class="btn secondary" href="{{ route('admin.module',['module'=>$module,'section'=>$section]) }}">پاک کردن</a>@endif</form>
        @if($rows->isNotEmpty())
        <div class="table-wrap"><table class="panel-table"><thead><tr>@foreach(array_keys((array)$rows->first()) as $column)<th>{{ $column }}</th>@endforeach</tr></thead><tbody>@foreach($rows as $row)<tr>@foreach((array)$row as $value)<td>{{ is_scalar($value)?\Illuminate\Support\Str::limit((string)$value,80):'' }}</td>@endforeach</tr>@endforeach</tbody></table></div>
        @else
        <div class="module-empty"><strong>هنوز داده‌ای برای نمایش در این بخش وجود ندارد.</strong><span>بخش فعال است و به‌محض ایجاد رکورد واقعی، داده‌ها همین‌جا نمایش داده می‌شوند.</span></div>
        @endif
    </section>
</div>
@push('styles')
<style>
.admin-module-page{display:grid;gap:18px}.module-tabs{display:flex;gap:8px;overflow:auto;padding:4px 0 10px}.module-tabs a{white-space:nowrap;padding:9px 13px;border:1px solid #e7ebf2;border-radius:10px;background:#fff;color:#475467;font-size:12px;font-weight:800}.module-tabs a.active{background:#eef3ff;color:#3157d5;border-color:#d7e1ff}.module-section-title{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:14px}.module-section-title h2{margin:0;font-size:16px}.module-section-title span{font-size:11px;color:#98a2b3}.module-actions{display:flex;flex-wrap:wrap;gap:8px}.module-search{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:14px}.module-search .form-control{min-width:260px}.module-empty{display:grid;gap:7px;text-align:center;padding:38px 18px;border:1px dashed #d9dee8;border-radius:16px;color:#667085;background:#fafbfc}.module-empty strong{color:#344054}.module-empty span{font-size:12px}@media(max-width:700px){.module-search .form-control{min-width:100%;flex:1}.module-search .btn{flex:1}.module-tabs{scrollbar-width:none}}
</style>
@endpush
@endsection
