@extends('layouts.app')

@section('title', $q ? 'جستجوی '.$q.' | فایل‌مارکت' : 'فروشگاه فایل‌مارکت')

@section('description','جستجو و خرید فایل‌های دیجیتال در فایل‌مارکت')

@section('content')

<div class="container search-page">

<h1>
{{ $q ? 'نتایج جستجو برای «'.$q.'»' : 'فروشگاه فایل‌ها' }}
</h1>

<form class="search big-search" action="{{ route('search') }}">
<input name="q" value="{{ $q }}"
placeholder="نام فایل مورد نظر را جستجو کنید">
<button class="btn">جستجو</button>
</form>

<div class="products">

@forelse($products as $product)

<article class="card">

<a class="card-link"
href="{{ route('product.show',$product) }}">

<div class="card-img">📁</div>

<div class="card-body">
<h2>{{ $product->title }}</h2>
<p class="muted">{{ $product->short_description }}</p>

<div class="card-bottom">
<span class="price">
{{ number_format($product->price) }} تومان
</span>
</div>
</div>

</a>

<form class="card-cart"
method="POST"
action="{{ route('cart.add',$product) }}">
@csrf
<button title="افزودن به سبد" aria-label="افزودن به سبد">🛒</button>
</form>

<a class="card-view"
href="{{ route('product.show',$product) }}"
title="مشاهده محصول"
aria-label="مشاهده محصول">↗</a>

</article>

@empty

<div class="empty">
محصولی پیدا نشد.
</div>

@endforelse

</div>

<div class="pagination">
{{ $products->links() }}
</div>

</div>

@endsection