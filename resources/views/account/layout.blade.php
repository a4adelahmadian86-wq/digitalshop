<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'حساب کاربری') | فروشگاه فایل</title>
    <link rel="stylesheet" href="{{ asset('css/account.css') }}">
    <link rel="stylesheet" href="{{ asset('css/unified.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ai-assistant.css') }}">
</head>
<body class="account-body">
<div class="account-shell">
    <aside class="account-sidebar" id="accountSidebar">
        <div class="account-brand"><span class="account-brand-mark">D</span><div><strong>حساب کاربری</strong><small>پنل خریدار</small></div></div>
        <div class="account-sidebar-user"><div class="account-avatar">{{ mb_substr(trim(($user->first_name ?? '').' '.($user->last_name ?? '')) ?: 'ک', 0, 1) }}</div><div><strong>{{ trim(($user->first_name ?? '').' '.($user->last_name ?? '')) ?: 'کاربر' }}</strong><small dir="ltr">{{ $user->phone }}</small></div></div>
        <nav class="account-nav">
            <a class="{{ request()->routeIs('account.dashboard') ? 'active' : '' }}" href="{{ route('account.dashboard') }}"><span>⌂</span> داشبورد</a>
            <a class="{{ request()->routeIs('account.orders*') ? 'active' : '' }}" href="{{ route('account.orders') }}"><span>▤</span> سفارش‌های من</a>
            <a class="{{ request()->routeIs('account.files') ? 'active' : '' }}" href="{{ route('account.files') }}"><span>↓</span> فایل‌های خریداری‌شده</a>
            <a class="{{ request()->routeIs('account.wallet') ? 'active' : '' }}" href="{{ route('account.wallet') }}"><span>◈</span> کیف پول</a>
            <a class="{{ request()->routeIs('account.notifications*') ? 'active' : '' }}" href="{{ route('account.notifications') }}"><span>◉</span> اعلان‌ها @if(($user->unreadNotifications()->count() ?? 0)>0)<b class="nav-count">{{ $user->unreadNotifications()->count() }}</b>@endif</a>
            <a class="{{ request()->routeIs('account.profile') ? 'active' : '' }}" href="{{ route('account.profile') }}"><span>○</span> پروفایل</a>
            <a class="{{ request()->routeIs('account.security') ? 'active' : '' }}" href="{{ route('account.security') }}"><span>⌁</span> امنیت حساب</a>
        </nav>
        <a class="account-store-link" href="{{ route('home') }}">← بازگشت به فروشگاه</a>
        <form method="POST" action="{{ route('logout') }}" class="account-logout">@csrf<button type="submit">خروج از حساب</button></form>
    </aside>
    <main class="account-main">
        <header class="account-header"><div class="account-header-right"><button class="account-menu" type="button" aria-label="منو" onclick="document.getElementById('accountSidebar').classList.toggle('open')">☰</button><div class="account-header-title"><span>حساب من</span><strong>{{ trim(($user->first_name ?? '').' '.($user->last_name ?? '')) ?: 'کاربر' }}</strong></div></div><div class="account-header-left"><a class="account-header-icon" href="{{ route('account.notifications') }}" aria-label="اعلان‌ها">◉ @if($user->unreadNotifications()->count())<i></i>@endif</a><div class="account-phone" dir="ltr">{{ $user->phone }}</div></div></header>
        <div class="account-content">@if(session('success'))<div class="account-alert success">{{ session('success') }}</div>@endif @if(session('error'))<div class="account-alert error">{{ session('error') }}</div>@endif @if($errors->any())<div class="account-alert error">{{ $errors->first() }}</div>@endif @yield('content')</div>
    </main>
</div>
<script src="{{ asset('js/ai-assistant.js') }}"></script>
<script>document.addEventListener('click',function(event){const sidebar=document.getElementById('accountSidebar'),menu=document.querySelector('.account-menu');if(window.innerWidth<=900&&sidebar&&sidebar.classList.contains('open')&&!sidebar.contains(event.target)&&menu&&!menu.contains(event.target))sidebar.classList.remove('open');});</script>
</body>
</html>
