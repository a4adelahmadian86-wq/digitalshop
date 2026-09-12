@extends('layouts.app')

@section('title', 'پیش‌نمایش ' . $product->title)

@section('content')
<div class="container" style="padding:40px 0;max-width:900px">
    <div style="background:#fff;border:1px solid #eee;border-radius:18px;padding:28px">
        <h1 style="margin-top:0;font-size:24px">پیش‌نمایش: {{ $product->title }}</h1>

        @if($error)
            <div style="background:#fff1f1;color:#b42318;padding:14px;border-radius:12px;margin:16px 0">
                {{ $error }}
            </div>
        @endif

        @if($previewAvailable)
            <p style="color:#667085;line-height:1.9">
                پیش‌نمایش محدود این فایل برای بررسی قبل از خرید در دسترس است.
                دانلود کامل پس از پرداخت فعال می‌شود.
            </p>
            <div style="margin-top:20px;padding:40px;text-align:center;background:#f7f8fc;border-radius:14px;border:1px dashed #d0d5dd">
                <div style="font-size:48px;margin-bottom:12px">📄</div>
                <strong>حالت پیش‌نمایش امن</strong>
                <p style="color:#667085;margin:10px 0 0">محتوای کامل فقط پس از خرید قابل دریافت است.</p>
            </div>
        @else
            <div style="background:#f7f8fc;padding:18px;border-radius:12px;color:#667085">
                برای این محصول هنوز فایل پیش‌نمایش ثبت نشده است.
            </div>
        @endif

        <div style="margin-top:24px;display:flex;gap:10px;flex-wrap:wrap">
            <a href="{{ route('product.show', $product) }}" class="btn" style="display:inline-block;padding:10px 16px;background:#6541d8;color:#fff;border-radius:12px">بازگشت به محصول</a>
            <form method="POST" action="{{ route('cart.add', $product) }}">
                @csrf
                <button type="submit" style="border:0;padding:10px 16px;background:#111827;color:#fff;border-radius:12px;cursor:pointer">افزودن به سبد</button>
            </form>
        </div>
    </div>
</div>
@endsection
