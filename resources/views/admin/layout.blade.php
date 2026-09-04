<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'پنل مدیریت') | فروشگاه فایل</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/unified.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ai-assistant.css') }}">
    @stack('styles')
</head>
<body>
@include('admin._original_layout_body')
<script src="{{ asset('js/ai-assistant.js') }}"></script>
@stack('scripts')
</body>
</html>