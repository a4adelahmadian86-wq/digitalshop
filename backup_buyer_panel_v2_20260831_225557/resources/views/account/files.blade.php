@extends('account.layout')
@section('title','فایل‌های خریداری‌شده')
@section('content')
<div class="account-head"><div><small>کتابخانه دیجیتال</small><h1>فایل‌های خریداری‌شده</h1><p>فایل‌هایی که از خریدهای شما قابل دریافت هستند.</p></div></div>
<section class="account-card"><div class="file-list">@forelse($files as $file)<div class="file-row"><div class="file-icon">↓</div><div class="file-info"><strong>{{ $file->product?->title ?? 'فایل محصول' }}</strong><small>{{ optional($file->created_at)->format('Y/m/d') }}</small></div><div class="file-meta"><small>دفعات دانلود: {{ $file->downloads }}</small>@if($file->expires_at)<small>انقضا: {{ optional($file->expires_at)->format('Y/m/d') }}</small>@endif</div><a class="btn primary" href="{{ route('download',$file->orderItem) }}">دانلود</a></div>@empty<div class="empty">هنوز فایلی برای دانلود ندارید.</div>@endforelse</div><div class="pagination">{{ $files->links() }}</div></section>
@endsection
