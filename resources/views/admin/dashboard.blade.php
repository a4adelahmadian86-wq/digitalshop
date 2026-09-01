@extends('admin.layout')

@section('title', 'داشبورد')

@section('content')

<div class="admin-page">


    {{-- Breadcrumb --}}

    <div class="admin-breadcrumb">

        <span>
            پنل مدیریت
        </span>

        <span class="admin-breadcrumb-separator">
            /
        </span>

        <strong>
            داشبورد
        </strong>

    </div>


    {{-- Page Header --}}

    <div class="admin-page-head">

        <div>

            <div class="admin-eyebrow">
                مدیریت فروشگاه
            </div>

            <h1>
                داشبورد
            </h1>

            <p>
                نمای کلی وضعیت فروشگاه و مواردی که نیاز به اقدام دارند.
            </p>

        </div>

    </div>


    {{-- =====================================================
         STATISTICS
    ====================================================== --}}

    <section class="admin-stat-grid">


        {{-- Sales --}}

        <div class="admin-stat-card">

            <div class="admin-stat-top">

                <span class="admin-stat-label">
                    فروش
                </span>

                <div class="admin-stat-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path d="M4 19V5"/>
                        <path d="M4 19h16"/>
                        <path d="m7 15 4-5 3 3 5-7"/>
                    </svg>

                </div>

            </div>

            <div class="admin-stat-value">

                <strong>
                    {{ number_format($stats['sales'] ?? 0) }}
                </strong>

                <small>
                    تومان
                </small>

            </div>

            <div class="admin-stat-footer">
                مجموع فروش ثبت‌شده
            </div>

        </div>


        {{-- Orders --}}

        <div class="admin-stat-card">

            <div class="admin-stat-top">

                <span class="admin-stat-label">
                    سفارش‌ها
                </span>

                <div class="admin-stat-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <rect
                            x="3"
                            y="5"
                            width="18"
                            height="14"
                            rx="2"
                        />

                        <path d="M3 10h18"/>

                        <path d="M7 15h4"/>
                    </svg>

                </div>

            </div>

            <div class="admin-stat-value">

                <strong>
                    {{ number_format($stats['orders'] ?? 0) }}
                </strong>

            </div>

            <div class="admin-stat-footer">
                سفارش‌های ثبت‌شده
            </div>

        </div>


        {{-- Products --}}

        <div class="admin-stat-card">

            <div class="admin-stat-top">

                <span class="admin-stat-label">
                    محصولات
                </span>

                <div class="admin-stat-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path d="M4 7.5 12 3l8 4.5v9L12 21l-8-4.5v-9Z"/>
                        <path d="M4.5 7.8 12 12l7.5-4.2"/>
                        <path d="M12 12v9"/>
                    </svg>

                </div>

            </div>

            <div class="admin-stat-value">

                <strong>
                    {{ number_format($stats['products'] ?? 0) }}
                </strong>

            </div>

            <div class="admin-stat-footer">
                محصولات موجود در سیستم
            </div>

        </div>


        {{-- Users --}}

        <div class="admin-stat-card">

            <div class="admin-stat-top">

                <span class="admin-stat-label">
                    کاربران
                </span>

                <div class="admin-stat-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <circle
                            cx="12"
                            cy="8"
                            r="3"
                        />

                        <path d="M5 20a7 7 0 0 1 14 0"/>
                    </svg>

                </div>

            </div>

            <div class="admin-stat-value">

                <strong>
                    {{ number_format($stats['users'] ?? 0) }}
                </strong>

            </div>

            <div class="admin-stat-footer">
                کاربران ثبت‌نام‌شده
            </div>

        </div>

    </section>


    {{-- =====================================================
         QUICK ACTIONS
    ====================================================== --}}

    <section class="admin-action-grid">


        <a
            href="{{ route('admin.products.create') }}"
            class="admin-action-card"
        >

            <div class="admin-action-icon">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path d="M12 5v14"/>
                    <path d="M5 12h14"/>
                </svg>

            </div>

            <div>

                <strong>
                    افزودن محصول
                </strong>

                <span>
                    ایجاد محصول جدید
                </span>

            </div>

        </a>


        <a
            href="{{ route('admin.categories.create') }}"
            class="admin-action-card"
        >

            <div class="admin-action-icon">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path d="M12 5v14"/>
                    <path d="M5 12h14"/>
                </svg>

            </div>

            <div>

                <strong>
                    افزودن دسته‌بندی
                </strong>

                <span>
                    مدیریت ساختار دسته‌ها
                </span>

            </div>

        </a>


        <a
            href="{{ route('admin.discounts.create') }}"
            class="admin-action-card"
        >

            <div class="admin-action-icon">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path d="m4 7 3-3h6l7 7-7 7-7-7V7Z"/>
                    <circle
                        cx="8.5"
                        cy="8.5"
                        r="1"
                    />
                </svg>

            </div>

            <div>

                <strong>
                    ایجاد کد تخفیف
                </strong>

                <span>
                    افزودن تخفیف جدید
                </span>

            </div>

        </a>


        <a
            href="{{ route('admin.storage.create') }}"
            class="admin-action-card"
        >

            <div class="admin-action-icon">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path d="M7 18a5 5 0 0 1 .6-9.96A6 6 0 0 1 19 10a4 4 0 0 1-1 7.9H7Z"/>
                    <path d="M12 12v6"/>
                    <path d="m9.5 14.5 2.5 2.5 2.5-2.5"/>
                </svg>

            </div>

            <div>

                <strong>
                    افزودن فضای ذخیره‌سازی
                </strong>

                <span>
                    مدیریت Storage
                </span>

            </div>

        </a>

    </section>


    {{-- =====================================================
         NEEDS ACTION
    ====================================================== --}}

    <section class="admin-box admin-needs-action">

        <div class="admin-box-head">

            <div>

                <span>
                    عملیات
                </span>

                <h2>
                    نیازمند اقدام
                </h2>

            </div>

        </div>


        <div class="admin-needs-list">


            {{-- Products --}}

            <a
                href="{{ route('admin.products.index') }}"
                class="admin-needs-item"
            >

                <div class="admin-needs-item-main">

                    <div class="admin-needs-item-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="M12 3 2.8 19h18.4L12 3Z"/>
                            <path d="M12 9v4"/>
                            <path d="M12 16.5h.01"/>
                        </svg>

                    </div>

                    <div>

                        <strong>
                            محصولات
                        </strong>

                        <span>
                            بررسی و مدیریت محصولات
                        </span>

                    </div>

                </div>

                <span class="admin-needs-count">
                    {{ number_format($stats['products'] ?? 0) }}
                </span>

            </a>


            {{-- Discounts --}}

            <a
                href="{{ route('admin.discounts.index') }}"
                class="admin-needs-item"
            >

                <div class="admin-needs-item-main">

                    <div class="admin-needs-item-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="m4 7 3-3h6l7 7-7 7-7-7V7Z"/>
                            <circle
                                cx="8.5"
                                cy="8.5"
                                r="1"
                            />
                        </svg>

                    </div>

                    <div>

                        <strong>
                            کدهای تخفیف
                        </strong>

                        <span>
                            کدهای ثبت‌شده
                        </span>

                    </div>

                </div>

                <span class="admin-needs-count">
                    {{ number_format($stats['discounts'] ?? 0) }}
                </span>

            </a>


            {{-- Storage --}}

            <a
                href="{{ route('admin.storage.index') }}"
                class="admin-needs-item"
            >

                <div class="admin-needs-item-main">

                    <div class="admin-needs-item-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="M7 18a5 5 0 0 1 .6-9.96A6 6 0 0 1 19 10a4 4 0 0 1-1 7.9H7Z"/>
                        </svg>

                    </div>

                    <div>

                        <strong>
                            ذخیره‌سازی
                        </strong>

                        <span>
                            فضاهای ذخیره‌سازی فعال
                        </span>

                    </div>

                </div>

                <span class="admin-needs-count">
                    {{ $storage->count() }}
                </span>

            </a>


            {{-- Notifications --}}

            <a
                href="#"
                class="admin-needs-item"
            >

                <div class="admin-needs-item-main">

                    <div class="admin-needs-item-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/>
                            <path d="M10 21h4"/>
                        </svg>

                    </div>

                    <div>

                        <strong>
                            اعلان‌ها
                        </strong>

                        <span>
                            مرکز اعلان‌های مدیریت
                        </span>

                    </div>

                </div>

                <span class="admin-needs-count">
                    ۰
                </span>

            </a>

        </div>

    </section>


    {{-- =====================================================
         STORAGE + DISCOUNTS
    ====================================================== --}}

    <div class="admin-dashboard-grid">


        {{-- Storage --}}

        <section class="admin-box">

            <div class="admin-box-head">

                <div>

                    <span>
                        فضای ذخیره‌سازی
                    </span>

                    <h2>
                        وضعیت Storage
                    </h2>

                </div>

                <a
                    href="{{ route('admin.storage.index') }}"
                    class="admin-secondary-btn"
                >
                    مدیریت
                </a>

            </div>


            @php

                $storagePercent =
                    ($storageCapacity ?? 0) > 0
                        ? min(
                            100,
                            round(
                                (($storageUsed ?? 0) / $storageCapacity) * 100,
                                1
                            )
                        )
                        : 0;

            @endphp


            <div class="storage-overview">

                <div class="storage-numbers">

                    <strong>
                        {{ number_format(($storageUsed ?? 0) / 1073741824, 2) }}
                        GB
                    </strong>

                    <span>
                        از
                        {{ number_format(($storageCapacity ?? 0) / 1073741824, 2) }}
                        GB
                    </span>

                </div>


                <div class="storage-bar">

                    <span
                        style="width: {{ $storagePercent }}%"
                    ></span>

                </div>


                <div class="storage-meta">

                    <span>
                        {{ $storage->count() }}
                        فضای ذخیره‌سازی فعال
                    </span>

                    <strong>
                        {{ $storagePercent }}٪
                    </strong>

                </div>

            </div>

        </section>


        {{-- Discounts --}}

        <section class="admin-box">

            <div class="admin-box-head">

                <div>

                    <span>
                        تخفیف
                    </span>

                    <h2>
                        کدهای تخفیف
                    </h2>

                </div>

                <a
                    href="{{ route('admin.discounts.index') }}"
                    class="admin-secondary-btn"
                >
                    مدیریت
                </a>

            </div>


            <div class="admin-stat-value">

                <strong>
                    {{ number_format($stats['discounts'] ?? 0) }}
                </strong>

                <small>
                    کد
                </small>

            </div>


            <p
                style="
                    color:var(--admin-text-muted);
                    font-size:11px;
                    margin:10px 0 0;
                "
            >
                تعداد کدهای تخفیف ثبت‌شده در فروشگاه
            </p>

        </section>

    </div>


    {{-- =====================================================
         SALES REPORT
    ====================================================== --}}

    <section class="admin-box">

        <div class="admin-box-head">

            <div>

                <span>
                    گزارش
                </span>

                <h2>
                    روند فروش
                </h2>

            </div>

        </div>


        <div class="chart-placeholder">

            نمودار فروش پس از تکمیل بخش گزارش‌ها در این قسمت قرار می‌گیرد.

        </div>

    </section>

</div>

@endsection