@extends('layouts.app')
@section('title', $q ? 'جستجوی '.$q.' | فایل‌مارکت' : 'فروشگاه فایل')
@section('description', 'فروشگاه یکپارچه فایل‌های دیجیتال با جستجوی هوشمند و پیشنهادهای مرتبط')
@section('content')
<div class="container ds-products-page">
    <header class="ds-products-head">
        <div><span class="ds-eyebrow">فروشگاه فایل</span><h1>{{ $q ? 'نتایج جستجو برای «'.$q.'»' : 'همه فایل‌ها' }}</h1><p>{{ $q ? 'نتایج بر اساس عنوان، توضیحات و محتوای واقعی فایل‌ها رتبه‌بندی شده‌اند.' : 'فایل‌های دیجیتال را با تجربه‌ای یکپارچه و ساده بررسی و خریداری کنید.' }}</p></div>
        <form class="ds-products-search" action="{{ route('search') }}" method="GET"><input name="q" value="{{ $q }}" placeholder="نام فایل یا نیازتان را جستجو کنید"><button type="submit">جستجو</button></form>
    </header>
    <div class="ds-products-toolbar"><strong>{{ number_format($products->total()) }} محصول</strong><div class="ds-products-sort"><a class="active" href="{{ request()->fullUrlWithQuery(['page'=>1]) }}">مرتبط‌ترین</a><a href="{{ request()->fullUrlWithQuery(['sort'=>'newest','page'=>1]) }}">جدیدترین</a><a href="{{ request()->fullUrlWithQuery(['sort'=>'cheap','page'=>1]) }}">ارزان‌ترین</a><a href="{{ request()->fullUrlWithQuery(['sort'=>'expensive','page'=>1]) }}">گران‌ترین</a></div></div>
    <div class="dj-products">
        @forelse($products as $product)
            <article class="dj-product">
                <a href="{{ route('product.show', $product) }}"><img src="{{ $product->thumbnail_url }}" alt="{{ $product->title }}" width="320" height="205" loading="lazy"><div class="dj-product-body"><div class="meta">{{ $product->category?->name ?: 'فایل دیجیتال' }} · #{{ $product->id }}</div><h3>{{ $product->title }}</h3><div class="price">{{ number_format($product->price) }} تومان</div></div></a>
                <form method="POST" action="{{ route('cart.add', $product) }}">@csrf<button class="cart" type="submit" aria-label="افزودن به سبد"><svg class="ds-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 4h2l2.2 10.2a2 2 0 0 0 2 1.6h7.6a2 2 0 0 0 2-1.6L20 7H6"></path><circle cx="9" cy="20" r="1"></circle><circle cx="17" cy="20" r="1"></circle></svg></button></form>
            </article>
        @empty
            <div class="ds-products-empty"><h2>محصولی پیدا نشد</h2><p>عبارت دیگری را امتحان کنید.</p></div>
        @endforelse
    </div>
    <div class="pagination">{{ $products->links() }}</div>
</div>
@endsection
@push('styles')
<style>
.ds-products-page{max-width:1280px;padding:28px 16px 80px}.ds-products-head{display:grid;grid-template-columns:1fr minmax(320px,480px);gap:28px;align-items:end;margin-bottom:22px}.ds-eyebrow{font-size:11px;color:#667085}.ds-products-head h1{font-size:27px;line-height:1.5;margin:5px 0}.ds-products-head p{font-size:12px;line-height:1.9;color:#667085;margin:0}.ds-products-search{display:flex;gap:8px}.ds-products-search input{flex:1;min-width:0;height:46px;border:1px solid #dfe3ea;border-radius:12px;padding:0 14px;background:#fff;font:inherit}.ds-products-search button{height:46px;border:0;border-radius:12px;background:#101828;color:#fff;padding:0 20px;font:inherit;font-weight:800;cursor:pointer}.ds-products-toolbar{display:flex;align-items:center;justify-content:space-between;gap:15px;border-bottom:1px solid #eaecf0;padding:0 2px 13px;margin-bottom:18px}.ds-products-toolbar strong{font-size:12px;color:#344054}.ds-products-sort{display:flex;gap:5px;flex-wrap:wrap}.ds-products-sort a{padding:7px 10px;border-radius:8px;color:#667085;font-size:11px}.ds-products-sort a.active{color:#304aca;background:#f1f4ff;font-weight:800}.dj-products{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px}.dj-product{position:relative;min-width:0;border:1px solid #e8ebf0;border-radius:16px;background:#fff;overflow:hidden;transition:transform .16s ease,box-shadow .16s ease,border-color .16s ease}.dj-product:hover{transform:translateY(-2px);box-shadow:0 14px 30px rgba(16,24,40,.08);border-color:#dfe4ed}.dj-product>a{display:block}.dj-product>a>img{display:block;width:100%;height:205px;object-fit:cover;background:#f6f7f9}.dj-product-body{padding:13px 14px 12px}.dj-product-body .meta{font-size:10px;color:#98a2b3;margin-bottom:7px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.dj-product-body h3{font-size:13px;line-height:1.9;color:#1d2939;margin:0 0 12px;min-height:49px}.dj-product-body .price{font-size:14px;font-weight:900;color:#101828}.dj-product>form{position:absolute;left:12px;bottom:11px}.dj-product .cart{width:40px;height:40px;display:grid;place-items:center;border:1px solid #e1e5eb;border-radius:11px;background:#fff;color:#344054;cursor:pointer;box-shadow:0 4px 12px rgba(16,24,40,.06)}.dj-product .cart:hover{color:#304aca;border-color:#bfcaff;background:#f7f8ff}.ds-products-empty{grid-column:1/-1;text-align:center;padding:70px 20px;border:1px solid #eaecf0;border-radius:18px;background:#fff}.ds-products-empty h2{font-size:18px;margin:0 0 7px}.ds-products-empty p{font-size:12px;color:#98a2b3;margin:0}@media(max-width:1050px){.dj-products{grid-template-columns:repeat(3,minmax(0,1fr))}}@media(max-width:760px){.ds-products-head{grid-template-columns:1fr}.dj-products{grid-template-columns:repeat(2,minmax(0,1fr))}.ds-products-toolbar{align-items:flex-start;flex-direction:column}}@media(max-width:480px){.dj-products{grid-template-columns:1fr}.ds-products-search button{padding:0 14px}.dj-product>a>img{height:220px}}
</style>
@endpush
