<!doctype html>
<html lang="fa" dir="rtl">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title','فروشگاه فایل')</title><meta name="description" content="@yield('description','فروشگاه حرفه‌ای فایل‌های دیجیتال')"><meta name="robots" content="index,follow"><link rel="canonical" href="{{ url()->current() }}">
<meta property="og:title" content="@yield('title','فروشگاه فایل')"><meta property="og:description" content="@yield('description','فروشگاه حرفه‌ای فایل‌های دیجیتال')"><meta property="og:type" content="website">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">@stack('styles')
<style>.support-fab{position:fixed;right:20px;bottom:20px;z-index:1100;width:50px;height:50px;border-radius:16px;background:#fff;color:#475467;border:1px solid #e4e7ec;box-shadow:0 12px 32px rgba(16,24,40,.13);display:grid;place-items:center;text-decoration:none;transition:.2s}.support-fab:hover{transform:translateY(-3px);color:#5b35d5;border-color:#cfc5ff}.site-main{min-height:55vh}@media(max-width:600px){.support-fab{right:12px;bottom:12px;width:45px;height:45px}}</style>
</head>
<body data-cart-added="{{ session('cart_added') ? '1' : '0' }}">
@include('partials.header')
<main class="site-main">@yield('content')</main>
@include('partials.footer')
<a class="support-fab" href="{{ auth()->check()?route('support.create'):route('login') }}" aria-label="پشتیبانی" title="پشتیبانی"><x-icon name="note" size="21" /></a>
@include('partials.ai-assistant')
<script src="{{ asset('js/app.js') }}"></script>@stack('scripts')
</body></html>
