@extends('admin.layout')

@section('title', 'جزئیات کاربر')

@section('content')
<div class="admin-page">
    <div class="admin-breadcrumb">
        <a href="{{ route('admin.users.index') }}">کاربران</a>
        <span class="admin-breadcrumb-separator">/</span>
        <span>جزئیات کاربر</span>
    </div>

    <section class="user-profile-header">
        <div class="user-profile-main">
            <div class="user-avatar-large">{{ mb_substr($user->first_name ?: 'ک', 0, 1) }}</div>
            <div>
                <div class="admin-eyebrow">حساب کاربری</div>
                <h1>{{ trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: 'بدون نام' }}</h1>
                <div class="user-phone"><span dir="ltr">{{ $user->phone }}</span></div>
            </div>
        </div>
        <div class="user-profile-actions">
            <span class="status-badge {{ $user->is_active ? 'active' : 'danger' }}">{{ $user->is_active ? 'حساب فعال' : 'حساب غیرفعال' }}</span>
            @if(auth()->user()->hasPermission('users.update'))
                <a href="{{ route('admin.users.edit', $user) }}" class="admin-primary-btn">ویرایش کاربر</a>
            @endif
        </div>
    </section>

    <div class="admin-stat-grid">
        <div class="admin-stat-card"><div class="admin-stat-top"><span class="admin-stat-label">سفارش‌ها</span><div class="admin-stat-icon">🛒</div></div><div class="admin-stat-value"><strong>{{ number_format($totalOrders) }}</strong><small>سفارش</small></div></div>
        <div class="admin-stat-card"><div class="admin-stat-top"><span class="admin-stat-label">فایل‌های خریداری‌شده</span><div class="admin-stat-icon">↓</div></div><div class="admin-stat-value"><strong>{{ number_format($purchasedFilesCount) }}</strong><small>فایل</small></div></div>
        @if($canPayments)
            <div class="admin-stat-card"><div class="admin-stat-top"><span class="admin-stat-label">مجموع خرید</span><div class="admin-stat-icon">﷼</div></div><div class="admin-stat-value"><strong>{{ number_format($totalPurchased ?? 0) }}</strong><small>تومان</small></div></div>
        @endif
        @if($canWallets)
            <div class="admin-stat-card"><div class="admin-stat-top"><span class="admin-stat-label">موجودی کیف پول</span><div class="admin-stat-icon">₮</div></div><div class="admin-stat-value"><strong>{{ number_format($user->wallet?->balance ?? 0) }}</strong><small>تومان</small></div></div>
        @endif
    </div>

    <div class="user-details-grid">
        <section class="admin-box">
            <div class="admin-box-head"><div><span>اطلاعات حساب</span><h2>مشخصات کاربر</h2></div></div>
            <div class="user-info-list">
                <div class="user-info-row"><span>نام و نام خانوادگی</span><strong>{{ trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: '—' }}</strong></div>
                <div class="user-info-row"><span>شماره موبایل</span><strong dir="ltr">{{ $user->phone }}</strong></div>
                <div class="user-info-row"><span>نقش</span><span class="status-badge {{ $user->role === 'admin' ? 'active' : 'inactive' }}">{{ $user->role }}</span></div>
                <div class="user-info-row"><span>وضعیت حساب</span><span class="status-badge {{ $user->is_active ? 'active' : 'danger' }}">{{ $user->is_active ? 'فعال' : 'غیرفعال' }}</span></div>
                <div class="user-info-row"><span>تأیید موبایل</span><span class="status-badge {{ $user->phone_verified_at ? 'active' : 'inactive' }}">{{ $user->phone_verified_at ? 'تأیید شده' : 'تأیید نشده' }}</span></div>
                <div class="user-info-row"><span>تاریخ عضویت</span><strong>{{ optional($user->created_at)->format('Y/m/d') }}</strong></div>
            </div>
        </section>

        @if(!$canWallets)
            <section class="admin-box"><div class="admin-box-head"><div><span>اطلاعات محدود</span><h2>وضعیت مالی</h2></div></div><div class="discount-used-info">اطلاعات کیف پول و تراکنش‌های مالی برای حساب شما مجاز نیست.</div></section>
        @endif
    </div>

    <section class="admin-box user-section">
        <div class="admin-box-head"><div><span>خریدها</span><h2>تاریخچه سفارش‌ها</h2></div></div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>سفارش</th>@if($canPayments)<th>مبلغ</th>@endif<th>وضعیت</th><th>محصولات</th><th>تاریخ</th></tr></thead>
                <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong></td>
                        @if($canPayments)<td>{{ number_format($order->total) }} تومان</td>@endif
                        <td>{{ $order->status }}</td>
                        <td>{{ $order->items->count() }} محصول</td>
                        <td>{{ optional($order->created_at)->format('Y/m/d H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="{{ $canPayments ? 5 : 4 }}" class="admin-empty">این کاربر هنوز سفارشی ثبت نکرده است.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:20px;">{{ $orders->links() }}</div>
    </section>

    <section class="admin-box user-section">
        <div class="admin-box-head"><div><span>محصولات دیجیتال</span><h2>فایل‌های خریداری‌شده</h2></div></div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>محصول</th><th>سفارش</th>@if($canPayments)<th>مبلغ</th>@endif<th>دانلود</th><th>تاریخ خرید</th></tr></thead>
                <tbody>
                @forelse($purchasedItems->unique('product_id') as $item)
                    <tr>
                        <td><strong>{{ $item->product?->title ?? 'محصول حذف شده' }}</strong></td>
                        <td>{{ $item->order?->order_number ?? '—' }}</td>
                        @if($canPayments)<td>{{ number_format($item->price) }} تومان</td>@endif
                        <td>{{ $item->downloads->count() }} بار</td>
                        <td>{{ optional($item->order?->created_at)->format('Y/m/d H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="{{ $canPayments ? 5 : 4 }}" class="admin-empty">فایل خریداری‌شده‌ای ثبت نشده است.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
