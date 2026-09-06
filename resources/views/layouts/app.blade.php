<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#ffffff">

    <title>@yield('title', 'فروشگاه فایل')</title>

    <meta
        name="description"
        content="@yield('description', 'فروشگاه حرفه‌ای فایل‌های دیجیتال')"
    >

    @if ($__env->hasSection('keywords'))
        <meta name="keywords" content="@yield('keywords')">
    @endif

    <meta name="robots" content="index,follow">

    <link
        rel="canonical"
        href="@yield('canonical', url()->current())"
    >

    <meta property="og:type" content="@yield('og_type', 'website')">

    <meta
        property="og:title"
        content="@yield('og_title', trim($__env->yieldContent('title', 'فروشگاه فایل')))"
    >

    <meta
        property="og:description"
        content="@yield('og_description', trim($__env->yieldContent('description', 'فروشگاه حرفه‌ای فایل‌های دیجیتال')))"
    >

    <meta
        property="og:url"
        content="@yield('canonical', url()->current())"
    >

    @if ($__env->hasSection('og_image'))
        <meta property="og:image" content="@yield('og_image')">
    @endif

    <meta property="og:site_name" content="فایل‌مارکت">

    @if ($__env->hasSection('og_image'))
        <meta name="twitter:card" content="summary_large_image">
    @else
        <meta name="twitter:card" content="summary">
    @endif

    <meta
        name="twitter:title"
        content="@yield('og_title', trim($__env->yieldContent('title', 'فروشگاه فایل')))"
    >

    <meta
        name="twitter:description"
        content="@yield('og_description', trim($__env->yieldContent('description', 'فروشگاه حرفه‌ای فایل‌های دیجیتال')))"
    >

    @if ($__env->hasSection('og_image'))
        <meta name="twitter:image" content="@yield('og_image')">
    @endif

    <style id="ds-critical">
        html {
            background: #fff;
        }

        body {
            margin: 0;
            background: #fff;
            color: #24262d;
            font-family: YekanBakh, Tahoma, Arial, sans-serif;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        button,
        input,
        textarea,
        select {
            box-sizing: border-box;
            font-family: inherit;
        }

        .site-header {
            position: relative;
            z-index: 1000;
            background: #fff;
            border-bottom: 1px solid #edf0f4;
        }

        .dj-header-main {
            background: #fff;
        }

        .dj-header-grid {
            min-height: 86px;
            display: grid;
            grid-template-columns: 220px minmax(420px, 1fr) 280px;
            align-items: center;
            gap: 26px;
        }

        .dj-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .dj-search {
            position: relative;
            z-index: 1205;
        }

        .dj-search-box {
            height: 52px;
            display: flex;
            align-items: center;
            border: 1px solid #e3e7ee;
            border-radius: 15px;
            background: #f7f8fa;
        }

        .dj-header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .dj-user {
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .dj-user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: #f3f5f8;
        }

        .dj-action-wrap {
            position: relative;
        }

        .dj-icon-action {
            width: 40px;
            height: 40px;
            display: grid;
            place-items: center;
            background: transparent;
            border: 0;
        }

        .dj-header-nav {
            border-top: 1px solid #f3f4f6;
            background: #fff;
        }

        .dj-nav-inner {
            min-height: 49px;
            display: flex;
            align-items: center;
        }

        .site-main {
            min-height: 50vh;
        }

        .dj-footer-capture {
            position: relative;
            box-sizing: border-box;
        }
    </style>

    <link rel="stylesheet" href="{{ asset('css/ds-typography.css') }}">
    <link rel="stylesheet" href="{{ asset('css/daneshjooyar-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/site-v2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ui-overrides.css') }}">
    <link rel="stylesheet" href="{{ asset('css/unified-pages.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ai-assistant.css') }}">
    <link rel="stylesheet" href="{{ asset('css/site-polish.css') }}">
    <link rel="stylesheet" href="{{ asset('css/site-polish-fix.css') }}">
    <link rel="stylesheet" href="{{ asset('css/site-capture-final.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header-final.css') }}">
    <link rel="stylesheet" href="{{ asset('css/frontend-typography.css') }}">
    <link rel="stylesheet" href="{{ asset('css/store-components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer-social-final.css') }}">
    <link rel="stylesheet" href="{{ asset('css/category-menu-final.css') }}">

    @stack('styles')

    @if ($__env->hasSection('structured_data'))
        @yield('structured_data')
    @endif
</head>

<body data-cart-added="{{ session('cart_added') ? '1' : '0' }}">

    @include('partials.header')

    <main class="site-main">
        @yield('content')
    </main>

    @include('partials.footer')

    @include('partials.ai-assistant')

    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/category-menu-final.js') }}" defer></script>
    <script src="{{ asset('js/product-feedback.js') }}" defer></script>

    @stack('scripts')

</body>
</html>
