@extends('dashboard.layout')

@section('title', 'داشبورد مدیریت')

@section('content')
<div class="fd-head">
    <div><div class="fd-eyebrow">مرکز کنترل FARAST</div><h1>داشبورد مدیریت</h1><p>وضعیت عملیاتی فروشگاه، کاربران و جریان سفارش‌ها در یک نگاه.</p></div>
    <div style="display:flex;gap:8px;flex-wrap:wrap">
        @if(auth()->user()->hasPermission('products.create'))<a class="fd-btn primary" href="{{ route('admin.products.create') }}">افزودن محصول</a>@endif
        @if(auth()->user()->hasPermission('users.view'))<a class="fd-btn" href="{{ route('admin.users.index') }}">مدیریت کاربران</a>@endif
    </div>
</div>
<div class="fd-grid">
    <div class="fd-stat"><small>فروش پرداخت‌شده</small><strong>{{ number_format($stats['sales']) }}</strong><span>تومان</span></div>
    <div class="fd-stat"><small>کل سفارش‌ها</small><strong>{{ number_format($stats['orders']) }}</strong><span>{{ number_format($stats['pending_orders']) }} در انتظار پرداخت</span></div>
    <div class="fd-stat"><small>کاربران</small><strong>{{ number_format($stats['users']) }}</strong><span>{{ number_format($stats['active_users']) }} حساب فعال</span></div>
    <div class="fd-stat"><small>محصولات</small><strong>{{ number_format($stats['products']) }}</strong><span>{{ number_format($stats['storage']) }} فضای ذخیره‌سازی فعال</span></div>
</div>
<div class="fd-panels">
    <section class="fd-card">
        <div class="fd-card-head"><div><small>عملیات اخیر</small><h2>آخرین سفارش‌ها</h2></div></div>
        <div class="fd-list">
            @forelse($recentOrders as $order)
                @if(auth()->user()->hasPermission('users.view'))
                    <a class="fd-row" href="{{ route('admin.users.show', $order->user_id) }}"><span><b>{{ $order->order_number }}</b><small>{{ trim(($order->user?->first_name ?? '') . ' ' . ($order->user?->last_name ?? '')) ?: 'کاربر' }} · {{ optional($order->created_at)->format('Y/m/d H:i') }}</small></span><span><b>{{ number_format($order->total) }} تومان</b><small>{{ $order->status }}</small></span></a>
                @else
                    <div class="fd-row"><span><b>{{ $order->order_number }}</b><small>{{ optional($order->created_at)->format('Y/m/d H:i') }}</small></span><span><b>{{ number_format($order->total) }} تومان</b><small>{{ $order->status }}</small></span></div>
                @endif
            @empty<div class="fd-empty">سفارشی ثبت نشده است.</div>@endforelse
        </div>
    </section>
    <section class="fd-card">
        <div class="fd-card-head"><div><small>اقدام سریع</small><h2>مدیریت</h2></div></div>
        <div class="fd-list">
            @if(auth()->user()->hasPermission('roles.view'))<a class="fd-row" href="{{ route('admin.access.index') }}"><span><b>نقش‌ها و دسترسی‌ها</b><small>مجوزهای صریح هر نقش</small></span><b>→</b></a>@endif
            @if(auth()->user()->hasPermission('discounts.manage'))<a class="fd-row" href="{{ route('admin.discounts.index') }}"><span><b>تخفیف‌ها</b><small>{{ number_format($stats['discounts']) }} کد ثبت‌شده</small></span><b>→</b></a>@endif
            @if(auth()->user()->hasPermission('storage.manage'))<a class="fd-row" href="{{ route('admin.storage.index') }}"><span><b>ذخیره‌سازی</b><small>{{ number_format($stats['storage']) }} provider فعال</small></span><b>→</b></a>@endif
            @if(auth()->user()->hasPermission('wallets.view'))<a class="fd-row" href="{{ route('admin.wallets.index') }}"><span><b>کیف پول‌ها</b><small>بررسی تراکنش‌های کاربران</small></span><b>→</b></a>@endif
        </div>
    </section>
</div>
@endsection