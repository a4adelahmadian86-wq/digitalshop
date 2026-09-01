<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'حساب کاربری') | فروشگاه</title>
    <link rel="stylesheet" href="{{ asset('css/account.css') }}">
</head>
<body class="account-body">
<div class="account-shell">
    <aside class="account-sidebar" id="accountSidebar">
        <div class="account-brand"><span class="account-brand-mark">D</span><div><strong>حساب کاربری</strong><small>پنل خریدار</small></div></div>
        <nav class="account-nav">
            <a class="{{ request()->routeIs('account.dashboard') ? 'active' : '' }}" href="{{ route('account.dashboard') }}">داشبورد</a>
            <a class="{{ request()->routeIs('account.orders*') ? 'active' : '' }}" href="{{ route('account.orders') }}">سفارش‌های من</a>
            <a class="{{ request()->routeIs('account.files') ? 'active' : '' }}" href="{{ route('account.files') }}">فایل‌های خریداری‌شده</a>
            <a class="{{ request()->routeIs('account.wallet') ? 'active' : '' }}" href="{{ route('account.wallet') }}">کیف پول</a>
            <a class="{{ request()->routeIs('account.notifications*') ? 'active' : '' }}" href="{{ route('account.notifications') }}">اعلان‌ها</a>
            <a class="{{ request()->routeIs('account.profile') ? 'active' : '' }}" href="{{ route('account.profile') }}">پروفایل</a>
            <a class="{{ request()->routeIs('account.security') ? 'active' : '' }}" href="{{ route('account.security') }}">امنیت حساب</a>
        </nav>
        <form method="POST" action="{{ route('logout') }}" class="account-logout">@csrf<button type="submit">خروج از حساب</button></form>
    </aside>
    <main class="account-main">
        <header class="account-header"><button class="account-menu" type="button" onclick="document.getElementById('accountSidebar').classList.toggle('open')">☰</button><div><span>حساب من</span><strong>{{ trim(($user->first_name ?? '').' '.($user->last_name ?? '')) ?: 'کاربر' }}</strong></div><div class="account-phone" dir="ltr">{{ $user->phone }}</div></header>
        <div class="account-content">
            @if(session('success'))<div class="account-alert success">{{ session('success') }}</div>@endif
            @if($errors->any())<div class="account-alert error">{{ $errors->first() }}</div>@endif
            @yield('content')
        </div>
    </main>
</div>
</body>
</html>
