@extends('account.layout')
@section('title','داشبورد')
@section('content')
<div class="account-head">
    <div><small>حساب کاربری</small><h1>سلام {{ $user->first_name ?: 'دوست عزیز' }} 👋</h1><p>خریدها، فایل‌ها، کیف پول و اعلان‌های شما در یک نگاه.</p></div>
    <a class="btn primary" href="{{ route('products.index') }}">ادامه خرید</a>
</div>

<div class="account-stats">
    <a href="{{ route('account.orders') }}"><small>همه سفارش‌ها</small><strong>{{ number_format($ordersCount) }}</strong><span>سفارش ثبت‌شده</span></a>
    <a href="{{ route('account.orders', ['status'=>'paid']) }}"><small>خریدهای تکمیل‌شده</small><strong>{{ number_format($paidOrdersCount) }}</strong><span>دسترسی فعال</span></a>
    <a href="{{ route('account.files') }}"><small>فایل‌های من</small><strong>{{ number_format($downloadsCount) }}</strong><span>قابل دریافت</span></a>
    <a href="{{ route('account.wallet') }}"><small>موجودی کیف پول</small><strong>{{ number_format((int)($user->wallet?->balance ?? 0)) }} <em>تومان</em></strong><span>موجودی فعلی</span></a>
</div>

@if($pendingOrdersCount > 0 || $unreadNotifications > 0)
<div class="account-attention">
    @if($pendingOrdersCount > 0)<a href="{{ route('account.orders', ['status'=>'pending']) }}"><b>{{ $pendingOrdersCount }}</b><span>سفارش نیازمند پیگیری دارید.</span>مشاهده سفارش‌ها →</a>@endif
    @if($unreadNotifications > 0)<a href="{{ route('account.notifications') }}"><b>{{ $unreadNotifications }}</b><span>اعلان خوانده‌نشده دارید.</span>مشاهده اعلان‌ها →</a>@endif
</div>
@endif

<div class="account-grid">
    <section class="account-card">
        <div class="card-title"><div><small>تاریخچه خرید</small><h2>آخرین سفارش‌ها</h2></div><a href="{{ route('account.orders') }}">همه سفارش‌ها</a></div>
        <div class="order-list">
            @forelse($recentOrders as $order)
                <a class="order-row" href="{{ route('account.orders.show',$order) }}">
                    <span><b>{{ $order->order_number }}</b><small>{{ optional($order->created_at)->format('Y/m/d H:i') }}</small></span>
                    <span><b>{{ number_format($order->total) }} تومان</b><small>{{ match($order->status){'paid'=>'پرداخت‌شده','completed'=>'تکمیل‌شده','pending'=>'در انتظار پرداخت','failed'=>'ناموفق','cancelled'=>'لغوشده',default=>$order->status} }}</small></span>
                </a>
            @empty<div class="empty">هنوز سفارشی ثبت نشده است.</div>@endforelse
        </div>
    </section>

    <section class="account-card welcome">
        <div class="welcome-icon">✓</div>
        <small>کتابخانه دیجیتال</small><h2>فایل‌های خریداری‌شده همیشه در دسترس شما هستند.</h2>
        <p>پس از پرداخت موفق، دسترسی فایل‌های هر خرید از همین پنل مدیریت می‌شود.</p>
        <div class="welcome-actions"><a class="btn primary" href="{{ route('account.files') }}">مشاهده فایل‌ها</a><a class="btn secondary" href="{{ route('account.wallet') }}">کیف پول</a></div>
    </section>
</div>
@endsection
