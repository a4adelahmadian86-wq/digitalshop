<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'فروشگاه فایل')</title>
    <meta name="description" content="@yield('description', 'فروشگاه فایل‌های دیجیتال و محصولات الکترونیکی')">
    <meta name="robots" content="index,follow">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'فروشگاه فایل')">
    <meta property="og:description" content="@yield('description', 'فروشگاه فایل‌های دیجیتال')">
    <meta property="og:type" content="website">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('styles')
</head>
<body data-cart-added="{{ session('cart_added') ? '1' : '0' }}">
    @include('partials.header')
    <main>@yield('content')</main>
    @include('partials.footer')
    @include('partials.ai-assistant')
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
