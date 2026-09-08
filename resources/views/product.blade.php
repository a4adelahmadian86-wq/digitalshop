@extends('layouts.app')

@section('title', $product->title . ' | فروشگاه فایل')

@section('description', Str::limit(strip_tags($product->short_description ?: $product->description), 155))

@section('content')
<div class="container product-page">
    <div class="product-detail">
        <div class="product-cover">
            @if($product->thumbnail)
                <img src="{{ route('product.image', $product) }}" alt="{{ $product->title }}" loading="eager" decoding="async" style="width:100%;height:100%;object-fit:cover;border-radius:inherit;display:block;">
            @else
                <span>📄</span>
            @endif
        </div>

        <div class="product-info">
            <div class="muted">محصول دیجیتال</div>
            <h1>{{ $product->title }}</h1>

            @if($product->short_description)
                <p>{{ $product->short_description }}</p>
            @endif

            <div class="product-price">{{ number_format($product->price) }} تومان</div>

            @if($downloadItem)
                <div class="purchased-badge"><span>✓</span> این فایل را خریداری کرده‌اید</div>
                <a href="{{ route('download', $downloadItem) }}" class="product-action product-download">
                    <span class="action-icon">↓</span><span>دانلود فایل</span>
                </a>
            @else
                <form method="POST" action="{{ route('cart.add', $product) }}">
                    @csrf
                    <button type="submit" class="product-action product-buy">
                        <span class="action-icon">🛒</span><span>افزودن به سبد</span>
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="product-description">
        <h2>توضیحات محصول</h2>
        {!! nl2br(e($product->description)) !!}
    </div>
</div>
@endsection
