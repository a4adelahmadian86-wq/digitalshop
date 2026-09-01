<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'پنل مدیریت')
        | فروشگاه فایل
    </title>

    <link
        rel="stylesheet"
        href="{{ asset('css/style.css') }}"
    >

    <style>

        .admin-layout {
            min-height: 100vh;
            display: flex;
            background: #f5f7fb;
        }

        .admin-sidebar {
            width: 250px;
            flex: 0 0 250px;
            background: #ffffff;
            border-left: 1px solid #e8ebf1;
            padding: 24px 16px;
            position: sticky;
            top: 0;
            height: 100vh;
        }

        .admin-brand {
            padding: 8px 12px 24px;
            border-bottom: 1px solid #eef0f4;
            margin-bottom: 18px;
        }

        .admin-brand strong {
            display: block;
            font-size: 21px;
        }

        .admin-brand span {
            color: #8992a3;
            font-size: 12px;
        }

        .admin-nav {
            display: grid;
            gap: 5px;
        }

        .admin-nav a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 13px;
            border-radius: 10px;
            color: #596273;
            text-decoration: none;
            transition: .2s;
        }

        .admin-nav a:hover,
        .admin-nav a.active {
            background: #f0f3ff;
            color: #3d5afe;
        }

        .admin-main {
            flex: 1;
            min-width: 0;
            padding: 32px;
        }

        .admin-page {
            max-width: 1450px;
            margin: auto;
        }

        .admin-page.narrow {
            max-width: 900px;
        }

        .admin-page-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 28px;
        }

        .admin-page-head h1 {
            margin: 5px 0;
            font-size: 30px;
        }

        .admin-page-head p {
            margin: 0;
            color: #8992a3;
        }

        .admin-eyebrow {
            color: #536dfe;
            font-size: 13px;
            font-weight: 700;
        }

        .admin-stat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 22px;
        }

        .admin-stat-card,
        .admin-box,
        .admin-table-card,
        .admin-form-card {
            background: #fff;
            border: 1px solid #e8ebf1;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(20, 30, 50, .04);
        }

        .admin-stat-card {
            padding: 22px;
        }

        .admin-stat-card span {
            display: block;
            color: #8992a3;
            margin-bottom: 12px;
            font-size: 13px;
        }

        .admin-stat-card strong {
            font-size: 27px;
        }

        .admin-stat-card small {
            font-size: 12px;
            color: #8992a3;
        }

        .admin-dashboard-grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .admin-box {
            padding: 24px;
        }

        .admin-box-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .admin-box-head h2 {
            margin: 5px 0 0;
        }

        .admin-primary-btn,
        .admin-secondary-btn {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            border-radius: 10px;
            padding: 11px 17px;
            text-decoration: none;
            border: 0;
            cursor: pointer;
            font-family: inherit;
            font-size: 14px;
        }

        .admin-primary-btn {
            background: #536dfe;
            color: #fff;
        }

        .admin-secondary-btn {
            background: #f2f4f8;
            color: #596273;
        }

        .admin-table-card {
            overflow: hidden;
        }

        .admin-table-head {
            padding: 22px;
            border-bottom: 1px solid #eef0f4;
        }

        .admin-table-head h2 {
            margin: 0;
        }

        .admin-table-wrap {
            overflow-x: auto;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }

        .admin-table th,
        .admin-table td {
            padding: 16px 18px;
            text-align: right;
            border-bottom: 1px solid #f0f1f4;
        }

        .admin-table th {
            background: #fafbfc;
            color: #7a8393;
            font-size: 13px;
        }

        .discount-code {
            font-family: monospace;
            font-weight: 700;
            direction: ltr;
            display: inline-block;
        }

        .status-badge {
            display: inline-flex;
            padding: 5px 9px;
            border-radius: 30px;
            font-size: 12px;
        }

        .status-badge.active {
            background: #e9f9ef;
            color: #1b8d48;
        }

        .status-badge.inactive {
            background: #f0f1f4;
            color: #707887;
        }

        .status-badge.warning {
            background: #fff5dc;
            color: #9b6b00;
        }

        .status-badge.danger {
            background: #ffebeb;
            color: #c62828;
        }

        .admin-actions {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .admin-actions form {
            margin: 0;
        }

        .action-btn {
            border: 0;
            border-radius: 7px;
            padding: 7px 10px;
            cursor: pointer;
            font-family: inherit;
            text-decoration: none;
            font-size: 12px;
        }

        .action-btn.edit {
            background: #edf1ff;
            color: #4355c7;
        }

        .action-btn.toggle {
            background: #f2f4f8;
            color: #596273;
        }

        .action-btn.delete {
            background: #ffeded;
            color: #c62828;
        }

        .admin-alert {
            padding: 13px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .admin-alert.success {
            background: #e9f9ef;
            color: #19733c;
        }

        .admin-alert.error {
            background: #ffeded;
            color: #b52222;
        }

        .admin-empty {
            text-align: center;
            padding: 70px 20px;
        }

        .admin-empty-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 15px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: #f0f3ff;
            color: #536dfe;
            font-size: 25px;
        }

        .admin-empty p {
            color: #8992a3;
            margin-bottom: 25px;
        }

        .admin-form-card {
            padding: 28px;
        }

        .admin-form {
            display: grid;
            gap: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .form-group {
            display: grid;
            gap: 8px;
        }

        .form-group label {
            font-weight: 700;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #dfe3ea;
            border-radius: 10px;
            padding: 12px 13px;
            font-family: inherit;
            outline: none;
            background: #fff;
        }

        .form-group input:focus {
            border-color: #536dfe;
        }

        .form-group textarea,
        .form-group select {
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #dfe3ea;
            border-radius: 10px;
            padding: 12px 13px;
            font-family: inherit;
            outline: none;
            background: #fff;
        }

        .form-group textarea:focus,
        .form-group select:focus {
            border-color: #536dfe;
        }

        .form-group small {
            color: #8992a3;
            font-size: 11px;
        }

        .checkbox-label {
            display: flex !important;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .checkbox-label input {
            width: auto;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding-top: 10px;
        }

        .discount-used-info {
            padding: 12px 15px;
            background: #f7f8fb;
            border-radius: 9px;
            color: #697386;
        }

        .storage-numbers {
            display: flex;
            align-items: baseline;
            gap: 7px;
            margin-bottom: 14px;
        }

        .storage-numbers strong {
            font-size: 27px;
        }

        .storage-numbers span {
            color: #8992a3;
        }

        .storage-bar {
            height: 9px;
            background: #eef0f5;
            border-radius: 10px;
            overflow: hidden;
        }

        .storage-bar span {
            display: block;
            height: 100%;
            background: #536dfe;
            border-radius: inherit;
        }

        .dashboard-big-number {
            font-size: 48px;
            font-weight: 800;
        }

        .chart-placeholder {
            min-height: 180px;
            margin-top: 20px;
            display: grid;
            place-items: center;
            border: 1px dashed #dfe3ea;
            border-radius: 12px;
            color: #9aa2b1;
        }

        @media(max-width: 1000px) {

            .admin-sidebar {
                width: 210px;
                flex-basis: 210px;
            }

            .admin-stat-grid {
                grid-template-columns: repeat(2, 1fr);
            }

        }

        @media(max-width: 750px) {

            .admin-layout {
                display: block;
            }

            .admin-sidebar {
                width: auto;
                height: auto;
                position: relative;
                border-left: 0;
                border-bottom: 1px solid #e8ebf1;
            }

            .admin-nav {
                display: flex;
                overflow-x: auto;
            }

            .admin-nav a {
                white-space: nowrap;
            }

            .admin-main {
                padding: 18px;
            }

            .admin-page-head {
                align-items: flex-start;
                flex-direction: column;
            }

            .admin-stat-grid,
            .admin-dashboard-grid,
            .form-row {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>

<body>

<div class="admin-layout">

    <aside class="admin-sidebar">

        <div class="admin-brand">

            <strong>فایل‌مارکت</strong>

            <span>
                پنل مدیریت
            </span>

        </div>

        <nav class="admin-nav">

            <a
                href="{{ route('admin.dashboard') }}"
                class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
            >
                ◉ داشبورد
            </a>

            <a href="#">
                ◫ سفارش‌ها
            </a>

            <a
    href="{{ route('admin.products.index') }}"
    class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}"
>
    ▣ محصولات
</a>

            <a
                href="{{ route('admin.categories.index') }}"
                class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
            >
                ◈ دسته‌بندی‌ها
            </a>

            <a
                href="{{ route('admin.discounts.index') }}"
                class="{{ request()->routeIs('admin.discounts.*') ? 'active' : '' }}"
            >
                % تخفیف‌ها
            </a>

<a
    href="{{ route('admin.storage.index') }}"
    class="{{ request()->routeIs('admin.storage.*') ? 'active' : '' }}"
>
    ☁ Storage
</a>

            <a href="#">
                ↓ دانلودها
            </a>

            <a href="#">
                ◎ کاربران
            </a>

            <a href="#">
                ⚙ تنظیمات
            </a>

        </nav>

        <div style="margin-top:25px;">

            <form
                method="POST"
                action="{{ route('admin.logout') }}"
            >

                @csrf

                <button
                    type="submit"
                    class="admin-secondary-btn"
                    style="width:100%;"
                >
                    خروج از پنل
                </button>

            </form>

        </div>

    </aside>

    <main class="admin-main">

        @yield('content')

    </main>

</div>

</body>

</html>