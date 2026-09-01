@extends('admin.layout')

@section('title', 'جزئیات کاربر')

@section('content')

<div class="admin-page">

    <div class="admin-breadcrumb">

        <a href="{{ route('admin.users.index') }}">
            کاربران
        </a>

        <span class="admin-breadcrumb-separator">
            /
        </span>

        <span>
            جزئیات کاربر
        </span>

    </div>


    {{-- Profile Header --}}

    <section class="user-profile-header">

        <div class="user-profile-main">

            <div class="user-avatar-large">

                {{ mb_substr(
                    $user->first_name ?: 'ک',
                    0,
                    1
                ) }}

            </div>

            <div>

                <div class="admin-eyebrow">
                    حساب کاربری
                </div>

                <h1>

                    {{ trim(
                        ($user->first_name ?? '') .
                        ' ' .
                        ($user->last_name ?? '')
                    ) ?: 'بدون نام' }}

                </h1>

                <div class="user-phone">

                    <span dir="ltr">
                        {{ $user->phone }}
                    </span>

                </div>

            </div>

        </div>


        <div class="user-profile-actions">

            @if($user->is_active)

                <span class="status-badge active">
                    حساب فعال
                </span>

            @else

                <span class="status-badge danger">
                    حساب غیرفعال
                </span>

            @endif


            <a
                href="{{ route('admin.users.edit', $user) }}"
                class="admin-primary-btn"
            >
                ویرایش کاربر
            </a>

        </div>

    </section>


    {{-- Stats --}}

    <div class="admin-stat-grid">

        <div class="admin-stat-card">

            <div class="admin-stat-top">

                <span class="admin-stat-label">
                    سفارش‌ها
                </span>

                <div class="admin-stat-icon">
                    🛒
                </div>

            </div>

            <div class="admin-stat-value">

                <strong>
                    {{ number_format($totalOrders) }}
                </strong>

                <small>
                    سفارش
                </small>

            </div>

        </div>


        <div class="admin-stat-card">

            <div class="admin-stat-top">

                <span class="admin-stat-label">
                    مجموع خرید
                </span>

                <div class="admin-stat-icon">
                    ﷼
                </div>

            </div>

            <div class="admin-stat-value">

                <strong>
                    {{ number_format($totalPurchased) }}
                </strong>

                <small>
                    تومان
                </small>

            </div>

        </div>


        <div class="admin-stat-card">

            <div class="admin-stat-top">

                <span class="admin-stat-label">
                    فایل‌های خریداری‌شده
                </span>

                <div class="admin-stat-icon">
                    ↓
                </div>

            </div>

            <div class="admin-stat-value">

                <strong>
                    {{ number_format($purchasedFilesCount) }}
                </strong>

                <small>
                    فایل
                </small>

            </div>

        </div>


        <div class="admin-stat-card">

            <div class="admin-stat-top">

                <span class="admin-stat-label">
                    موجودی کیف پول
                </span>

                <div class="admin-stat-icon">
                    ₮
                </div>

            </div>

            <div class="admin-stat-value">

                <strong>
                    {{ number_format($user->wallet?->balance ?? 0) }}
                </strong>

                <small>
                    تومان
                </small>

            </div>

        </div>

    </div>


    <div class="user-details-grid">


        {{-- Account Information --}}

        <section class="admin-box">

            <div class="admin-box-head">

                <div>

                    <span>
                        اطلاعات حساب
                    </span>

                    <h2>
                        مشخصات کاربر
                    </h2>

                </div>

            </div>


            <div class="user-info-list">

                <div class="user-info-row">

                    <span>
                        نام و نام خانوادگی
                    </span>

                    <strong>

                        {{ trim(
                            ($user->first_name ?? '') .
                            ' ' .
                            ($user->last_name ?? '')
                        ) ?: '—' }}

                    </strong>

                </div>


                <div class="user-info-row">

                    <span>
                        شماره موبایل
                    </span>

                    <strong dir="ltr">
                        {{ $user->phone }}
                    </strong>

                </div>


                <div class="user-info-row">

                    <span>
                        نقش
                    </span>

                    @if($user->role === 'admin')

                        <span class="status-badge active">
                            مدیر
                        </span>

                    @else

                        <span class="status-badge inactive">
                            کاربر
                        </span>

                    @endif

                </div>


                <div class="user-info-row">

                    <span>
                        وضعیت حساب
                    </span>

                    @if($user->is_active)

                        <span class="status-badge active">
                            فعال
                        </span>

                    @else

                        <span class="status-badge danger">
                            غیرفعال
                        </span>

                    @endif

                </div>


                <div class="user-info-row">

                    <span>
                        تأیید موبایل
                    </span>

                    @if($user->phone_verified_at)

                        <span class="status-badge active">
                            تأیید شده
                        </span>

                    @else

                        <span class="status-badge inactive">
                            تأیید نشده
                        </span>

                    @endif

                </div>


                <div class="user-info-row">

                    <span>
                        تاریخ عضویت
                    </span>

                    <strong>
                        {{ optional($user->created_at)->format('Y/m/d') }}
                    </strong>

                </div>

            </div>

        </section>


        {{-- Wallet --}}

        <section class="admin-box">

            <div class="admin-box-head">

                <div>

                    <span>
                        کیف پول
                    </span>

                    <h2>
                        وضعیت مالی کاربر
                    </h2>

                </div>

            </div>


            <div class="wallet-summary">

                <div class="wallet-balance">

                    <span>
                        موجودی فعلی
                    </span>

                    <strong>
                        {{ number_format(
                            $user->wallet?->balance ?? 0
                        ) }}
                    </strong>

                    <small>
                        تومان
                    </small>

                </div>


                @if($user->wallet)

                    <div class="user-info-row">

                        <span>
                            وضعیت کیف پول
                        </span>

                        @if($user->wallet->is_active)

                            <span class="status-badge active">
                                فعال
                            </span>

                        @else

                            <span class="status-badge danger">
                                غیرفعال
                            </span>

                        @endif

                    </div>

                @else

                    <div class="discount-used-info">

                        کیف پول هنوز برای این کاربر ایجاد نشده است.

                    </div>

                @endif

            </div>

        </section>

    </div>


    {{-- Orders --}}

    <section class="admin-box user-section">

        <div class="admin-box-head">

            <div>

                <span>
                    خریدها
                </span>

                <h2>
                    تاریخچه سفارش‌ها
                </h2>

            </div>

        </div>


        <div class="admin-table-wrap">

            <table class="admin-table">

                <thead>

                    <tr>

                        <th>
                            سفارش
                        </th>

                        <th>
                            مبلغ
                        </th>

                        <th>
                            وضعیت
                        </th>

                        <th>
                            محصولات
                        </th>

                        <th>
                            تاریخ
                        </th>

                    </tr>

                </thead>


                <tbody>

                @forelse($orders as $order)

                    <tr>

                        <td>

                            <strong>
                                {{ $order->order_number }}
                            </strong>

                        </td>


                        <td>

                            {{ number_format($order->total) }}

                            تومان

                        </td>


                        <td>

                            @if($order->status === 'paid')

                                <span class="status-badge active">
                                    پرداخت شده
                                </span>

                            @elseif($order->status === 'pending')

                                <span class="status-badge inactive">
                                    در انتظار پرداخت
                                </span>

                            @else

                                <span class="status-badge danger">
                                    {{ $order->status }}
                                </span>

                            @endif

                        </td>


                        <td>

                            {{ $order->items->count() }}

                            محصول

                        </td>


                        <td>

                            {{ optional(
                                $order->created_at
                            )->format('Y/m/d H:i') }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="5"
                            class="admin-empty"
                        >
                            این کاربر هنوز سفارشی ثبت نکرده است.
                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        <div style="margin-top:20px;">

            {{ $orders->links() }}

        </div>

    </section>


    {{-- Purchased Files --}}

    <section class="admin-box user-section">

        <div class="admin-box-head">

            <div>

                <span>
                    محصولات دیجیتال
                </span>

                <h2>
                    فایل‌های خریداری‌شده
                </h2>

            </div>

        </div>


        <div class="admin-table-wrap">

            <table class="admin-table">

                <thead>

                    <tr>

                        <th>
                            محصول
                        </th>

                        <th>
                            سفارش
                        </th>

                        <th>
                            مبلغ
                        </th>

                        <th>
                            دانلود
                        </th>

                        <th>
                            تاریخ خرید
                        </th>

                    </tr>

                </thead>


                <tbody>

                @forelse(
                    $purchasedItems->unique('product_id')
                    as $item
                )

                    <tr>

                        <td>

                            <strong>
                                {{ $item->product?->title ?? 'محصول حذف شده' }}
                            </strong>

                        </td>


                        <td>

                            {{ $item->order?->order_number ?? '—' }}

                        </td>


                        <td>

                            {{ number_format($item->price) }}

                            تومان

                        </td>


                        <td>

                            @if($item->downloads->count())

                                <span class="status-badge active">

                                    {{ $item->downloads->count() }}

                                    بار

                                </span>

                            @else

                                <span class="status-badge inactive">
                                    بدون دانلود
                                </span>

                            @endif

                        </td>


                        <td>

                            {{ optional(
                                $item->created_at
                            )->format('Y/m/d') }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="5"
                            class="admin-empty"
                        >
                            هنوز فایلی برای این کاربر ثبت نشده است.
                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </section>


    <div class="form-actions">

        <a
            href="{{ route('admin.users.index') }}"
            class="admin-secondary-btn"
        >
            بازگشت به کاربران
        </a>

        <a
            href="{{ route('admin.users.edit', $user) }}"
            class="admin-primary-btn"
        >
            ویرایش کاربر
        </a>

    </div>

</div>

@endsection