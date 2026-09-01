@extends('admin.layout')

@section('title', 'داشبورد')

@section('content')

<div class="admin-page">

    <div class="admin-page-head">

        <div>
            <div class="admin-eyebrow">مدیریت فروشگاه</div>

            <h1>داشبورد</h1>

            <p>
                نمای کلی وضعیت فروشگاه شما
            </p>
        </div>

    </div>

    <section class="admin-stat-grid">

        <div class="admin-stat-card">
            <span>فروش</span>

            <strong>
                {{ number_format($stats['sales']) }}
                <small>تومان</small>
            </strong>
        </div>

        <div class="admin-stat-card">
            <span>سفارش‌ها</span>
            <strong>{{ number_format($stats['orders']) }}</strong>
        </div>

        <div class="admin-stat-card">
            <span>محصولات</span>
            <strong>{{ number_format($stats['products']) }}</strong>
        </div>

        <div class="admin-stat-card">
            <span>کاربران</span>
            <strong>{{ number_format($stats['users']) }}</strong>
        </div>

    </section>

    <div class="admin-dashboard-grid">

        <section class="admin-box">

            <div class="admin-box-head">

                <div>
                    <span class="muted">Storage</span>
                    <h2>فضای ذخیره‌سازی</h2>
                </div>

                <a
                    href="#"
                    class="admin-secondary-btn"
                >
                    مدیریت Storage
                </a>

            </div>

            @php
                $storagePercent =
                    $storageCapacity > 0
                        ? min(
                            100,
                            round(
                                ($storageUsed / $storageCapacity) * 100,
                                1
                            )
                        )
                        : 0;
            @endphp

            <div class="storage-overview">

                <div class="storage-numbers">

                    <strong>
                        {{ number_format($storageUsed / 1073741824, 2) }}
                        GB
                    </strong>

                    <span>
                        از
                        {{ number_format($storageCapacity / 1073741824, 2) }}
                        GB
                    </span>

                </div>

                <div class="storage-bar">
                    <span
                        style="width: {{ $storagePercent }}%"
                    ></span>
                </div>

                <p class="muted">
                    {{ $storage->count() }}
                    فضای ذخیره‌سازی فعال
                </p>

            </div>

        </section>

        <section class="admin-box">

            <div class="admin-box-head">

                <div>
                    <span class="muted">Discount</span>
                    <h2>کدهای تخفیف</h2>
                </div>

                <a
                    href="{{ route('admin.discounts.index') }}"
                    class="admin-secondary-btn"
                >
                    مدیریت
                </a>

            </div>

            <div class="dashboard-big-number">
                {{ $stats['discounts'] }}
            </div>

            <p class="muted">
                کد تخفیف ثبت‌شده در فروشگاه
            </p>

        </section>

    </div>

    <section class="admin-box dashboard-placeholder">

        <div>
            <span class="muted">گزارش فروش</span>
            <h2>روند فروش</h2>
        </div>

        <div class="chart-placeholder">
            نمودار فروش در این بخش اضافه خواهد شد.
        </div>

    </section>

</div>

@endsection