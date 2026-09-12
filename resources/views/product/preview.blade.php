@extends('layouts.app')
@section('title', 'پیش‌نمایش ' . $product->title)
@section('content')
<div class="container" style="padding:40px 0;max-width:900px">
  <div style="background:#fff;border:1px solid #eee;border-radius:18px;padding:28px">
    <h1 style="margin-top:0">پیش‌نمایش: {{ $product->title }}</h1>
    @if(!empty($error))
      <div style="background:#fff1f1;color:#b42318;padding:12px;border-radius:12px">{{ $error }}</div>
    @endif
    @if($previewAvailable)
      <p style="color:#667085">پیش‌نمایش محدود قبل از خرید. دانلود کامل پس از پرداخت فعال می‌شود.</p>
      <div style="margin-top:18px;padding:36px;text-align:center;background:#f7f8fc;border-radius:14px;border:1px dashed #d0d5dd">
        <div style="font-size:42px">📄</div>
        <strong>حالت پیش‌نمایش امن</strong>
      </div>
    @else
      <div style="background:#f7f8fc;padding:16px;border-radius:12px;color:#667085">فایل پیش‌نمایش ثبت نشده است.</div>
    @endif
    <div style="margin-top:20px">
      <a href="{{ route('product.show', $product) }}" class="btn">بازگشت به محصول</a>
    </div>
  </div>
</div>
@endsection
