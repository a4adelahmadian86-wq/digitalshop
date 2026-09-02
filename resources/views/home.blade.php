@extends('layouts.app')

@section('title','فایل‌مارکت | مرجع فایل‌های دیجیتال و آموزشی')
@section('description','خرید و دانلود فایل‌های دیجیتال، آموزشی، گرافیکی و کاربردی با دسترسی سریع و مطمئن')

@section('content')
<div class="home-page">
    <section class="hero hero-market">
        <div class="container hero-grid">
            <div class="hero-copy">
                <span class="eyebrow">بازار تخصصی فایل‌های دیجیتال</span>
                <h1>فایل مورد نیازت را<br><strong>سریع پیدا کن</strong></h1>
                <p>از میان فایل‌های آموزشی، کاربردی و تخصصی انتخاب کن، آنلاین خرید کن و بلافاصله به محصولت دسترسی داشته باش.</p>
                <form class="hero-search" method="GET" action="{{ route('search') }}">
                    <span>⌕</span>
                    <input name="q" placeholder="نام فایل، موضوع یا کلمه کلیدی..." aria-label="جستجوی فایل">
                    <button type="submit">جستجو</button>
                </form>
                <div class="hero-tags">
                    <span>پیشنهادهای محبوب:</span>
                    <a href="{{ route('products.index') }}">ورد</a>
                    <a href="{{ route('products.index') }}">اکسل</a>
                    <a href="{{ route('products.index') }}">پاورپوینت</a>
                    <a href="{{ route('products.index') }}">پروژه</a>
                </div>
            </div>
            <div class="hero-visual" aria-hidden="true">
                <div class="hero-orbit orbit-one"></div>
                <div class="hero-orbit orbit-two"></div>
                <div class="hero-card hero-card-main">
                    <span class="hero-card-icon">▣</span>
                    <strong>فایل‌های کاربردی</strong>
                    <small>برای کار، دانشگاه و کسب‌وکار</small>
                </div>
                <div class="hero-float hero-float-one">✓ دانلود سریع</div>
                <div class="hero-float hero-float-two">★ فایل‌های منتخب</div>
            </div>
        </div>
    </section>

    <section class="trust-strip">
        <div class="container trust-grid">
            <div><span>✓</span><div><strong>دسترسی فوری</strong><small>بعد از خرید</small></div></div>
            <div><span>▣</span><div><strong>فایل‌های متنوع</strong><small>برای نیازهای مختلف</small></div></div>
            <div><span>↻</span><div><strong>خرید مطمئن</strong><small>فرآیند امن پرداخت</small></div></div>
            <div><span>♧</span><div><strong>پشتیبانی</strong><small>در کنار شما</small></div></div>
        </div>
    </section>

    <div class="container">
        <section class="section category-section">
            <div class="section-head-modern">
                <div><span class="section-kicker">انتخاب سریع</span><h2>چه چیزی نیاز داری؟</h2></div>
                <a href="{{ route('products.index') }}">همه دسته‌بندی‌ها ←</a>
            </div>
            <div class="categories-modern">
                @foreach($categories as $category)
                    <a class="category-modern" href="{{ route('products.index') }}">
                        <span class="category-icon">◈</span>
                        <span class="category-text"><strong>{{ $category->name }}</strong><small>مشاهده فایل‌ها</small></span>
                        <span class="category-arrow">←</span>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="section product-section">
            <div class="section-head-modern">
                <div><span class="section-kicker">تازه‌های فروشگاه</span><h2>جدیدترین فایل‌ها</h2></div>
                <a href="{{ route('products.index') }}">مشاهده همه ←</a>
            </div>
            <div class="products-modern">
                @forelse($products as $product)
                    <article class="product-card-modern">
                        <a href="{{ route('product.show',$product) }}" class="product-media">
                            @if($product->thumbnail)
                                <img src="{{ asset($product->thumbnail) }}" alt="{{ $product->title }}" loading="lazy">
                            @else
                                <div class="product-placeholder"><span>▤</span><small>فایل دیجیتال</small></div>
                            @endif
                            <span class="product-badge">جدید</span>
                        </a>
                        <div class="product-card-body">
                            <div class="product-category">{{ optional($product->category)->name ?? 'فایل دیجیتال' }}</div>
                            <h3><a href="{{ route('product.show',$product) }}">{{ $product->title }}</a></h3>
                            <p>{{ \Illuminate\Support\Str::limit($product->short_description, 92) }}</p>
                            <div class="product-meta">
                                <strong>{{ number_format($product->price) }} <small>تومان</small></strong>
                                <form method="POST" action="{{ route('cart.add',$product) }}">
                                    @csrf
                                    <button type="submit" aria-label="افزودن به سبد">🛒</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="empty-state"><strong>هنوز محصولی منتشر نشده است.</strong><span>به‌زودی فایل‌های جدید اینجا نمایش داده می‌شوند.</span></div>
                @endforelse
            </div>
        </section>

        <section class="promo-banner">
            <div><span class="section-kicker">برای شروع</span><h2>فایل مناسب خودت را پیدا کن</h2><p>در فروشگاه جستجو کن و از بین محصولات منتشرشده انتخاب کن.</p></div>
            <a href="{{ route('products.index') }}">ورود به فروشگاه <span>←</span></a>
        </section>

        <section class="section why-section">
            <div class="section-head-modern centered"><div><span class="section-kicker">چرا فایل‌مارکت؟</span><h2>یک تجربه ساده برای خرید فایل</h2></div></div>
            <div class="why-grid">
                <div class="why-card"><span>01</span><strong>جستجوی سریع</strong><p>محصول مورد نیازت را با جستجو و دسته‌بندی سریع پیدا کن.</p></div>
                <div class="why-card"><span>02</span><strong>خرید آسان</strong><p>فرآیند خرید و پرداخت ساده طراحی شده تا بدون دردسر انجام شود.</p></div>
                <div class="why-card"><span>03</span><strong>دسترسی پس از خرید</strong><p>فایل خریداری‌شده از حساب کاربری قابل دسترسی خواهد بود.</p></div>
            </div>
        </section>
    </div>
</div>
@endsection