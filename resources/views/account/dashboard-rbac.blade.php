@extends('dashboard.layout')

@section('title', 'داشبورد')

@section('content')
<div class="fd-head">
    <div><div class="fd-eyebrow">حساب کاربری</div><h1>سلام {{ $user->first_name ?: 'دوست عزیز' }} 👋</h1><p>خریدها، فایل‌ها، کیف پول و اعلان‌های شما در یک فضای یکپارچه.</p></div>
    <a class="fd-btn primary" href="{{ route('products.index') }}">ادامه خرید</a>
</div>

@if($pendingOrdersCount > 0 || $unreadNotifications > 0)
    <div class="fd-alert fd-danger" role="status">
        @if($pendingOrdersCount > 0) {{ number_format($pendingOrdersCount) }} سفارش در انتظار پیگیری دارید. @endif
        @if($unreadNotifications > 0) {{ number_format($unreadNotifications) }} اعلان خوانده‌نشده دارید. @endif
    </div>
@endif

<div class="fd-grid">
    <a class="fd-stat" style="text-decoration:none" href="{{ route('account.orders') }}"><small>همه سفارش‌ها</small><strong>{{ number_format($ordersCount) }}</strong><span>سفارش ثبت‌شده</span></a>
    <a class="fd-stat" style="text-decoration:none" href="{{ route('account.orders', ['status'=>'paid']) }}"><small>خریدهای تکمیل‌شده</small><strong>{{ number_format($paidOrdersCount) }}</strong><span>دسترسی فعال</span></a>
    <a class="fd-stat" style="text-decoration:none" href="{{ route('account.files') }}"><small>فایل‌های من</small><strong>{{ number_format($downloadsCount) }}</strong><span>قابل دریافت</span></a>
    <a class="fd-stat" style="text-decoration:none" href="{{ route('account.wallet') }}"><small>موجودی کیف پول</small><strong>{{ number_format((int)($user->wallet?->balance ?? 0)) }}</strong><span>تومان</span></a>
</div>

<div class="fd-panels">
    <section class="fd-card">
        <div class="fd-card-head"><div><small>تاریخچه خرید</small><h2>آخرین سفارش‌ها</h2></div><a href="{{ route('account.orders') }}" style="font-size:10px;color:var(--fd-primary)">همه</a></div>
        <div class="fd-list">
            @forelse($recentOrders as $order)
                <a class="fd-row" href="{{ route('account.orders.show', $order) }}"><span><b>{{ $order->order_number }}</b><small>{{ optional($order->created_at)->format('Y/m/d H:i') }}</small></span><span><b>{{ number_format($order->total) }} تومان</b><small>{{ $order->status }}</small></span></a>
            @empty<div class="fd-empty">هنوز سفارشی ثبت نشده است.</div>@endforelse
        </div>
    </section>
    <section class="fd-card">
        <div class="fd-card-head"><div><small>دسترسی سریع</small><h2>حساب من</h2></div></div>
        <div class="fd-list">
            <a class="fd-row" href="{{ route('account.files') }}"><span><b>فایل‌های من</b><small>دسترسی به خریدهای دیجیتال</small></span><b>→</b></a>
            <a class="fd-row" href="{{ route('account.wallet') }}"><span><b>کیف پول</b><small>موجودی و شارژ حساب</small></span><b>→</b></a>
            <a class="fd-row" href="{{ route('account.notifications') }}"><span><b>اعلان‌ها</b><small>{{ number_format($unreadNotifications) }} خوانده‌نشده</small></span><b>→</b></a>
            <a class="fd-row" href="{{ route('account.profile') }}"><span><b>پروفایل و امنیت</b><small>اطلاعات شخصی و رمز عبور</small></span><b>→</b></a>
        </div>
    </section>
</div>
@endsection