@extends('layouts.app')

@section('title','فروشگاه فایل | فایل‌های دیجیتال')

@section('description',
'خرید و دانلود فایل‌های دیجیتال، آموزشی، گرافیکی و کاربردی'
)

@section('content')

<div class="container">

<section class="hero">

<h1>فایل مورد نیازت را پیدا کن</h1>

<p>
محصولات دیجیتال کاربردی را پیدا کن، خریداری کن و بلافاصله دریافت کن.
</p>

<form class="search"
action="{{ route('search') }}">

<input name="q"
placeholder="جستجوی فایل..."
aria-label="جستجوی فایل">

<button class="btn">جستجو</button>

</form>

</section>

<section class="section">

<div class="section-head">
<h2>دسته‌بندی‌ها</h2>
</div>

<div class="categories">

@foreach($categories as $category)

<a class="category" href="#">
<span>◈</span>
<strong>{{ $category->name }}</strong>
</a>

@endforeach

</div>

</section>

<section class="section">

<div class="section-head">
<h2>جدیدترین فایل‌ها</h2>

<a href="{{ route('search') }}"
class="muted">مشاهده همه ←</a>
</div>

<div class="products">

@foreach($products as $product)

<article class="card">

<a class="card-link"
href="{{ route('product.show',$product) }}">

<div class="card-img">📁</div>

<div class="card-body">

<h2>{{ $product->title }}</h2>

<p class="muted">
{{ $product->short_description }}
</p>

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

<button
title="افزودن به سبد"
aria-label="افزودن به سبد">🛒</button>

</form>

<a class="card-view"
href="{{ route('product.show', $product) }}"
title="مشاهده محصول"
aria-label="مشاهده محصول">↗</a>

</article>

@endforeach

</div>

</section>

</div>

@endsection