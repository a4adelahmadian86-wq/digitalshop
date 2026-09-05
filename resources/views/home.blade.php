@extends('layouts.app')

@section('title','فروشگاه فایل | مرجع فایل‌های دیجیتال')
@section('description','خرید، بررسی و دسترسی سریع به فایل‌های دیجیتال با جستجوی هوشمند و دسته‌بندی‌های کاربردی')

@section('content')
<div class="dj-home">
    <section class="dj-hero container">
        <div class="dj-hero-copy">
            <span class="hero-kicker">مرجع فایل‌های دیجیتال</span>
            <h1>فایل مورد نیازت را <br><b>سریع و مطمئن</b> پیدا کن</h1>
            <p>جستجو کن، پیش‌نمایش را ببین، محتوای واقعی فایل را بررسی کن و با کمک دستیار هوشمند بهترین گزینه را انتخاب کن.</p>
            <form class="dj-hero-search" action="{{ route('search') }}">
                <input name="q" placeholder="مثلاً فایل اکسل حسابداری، پروژه PHP یا گزارش آماده">
                <button class="dj-btn" type="submit"><x-icon name="search" size="18"/> جستجو</button>
            </form>
            <div class="dj-quick">
                <a href="{{ route('search') }}"><span class="quick-icon"><x-icon name="search" size="23"/></span><h3>جستجوی هوشمند</h3><p>جستجو در عنوان و محتوای واقعی فایل</p></a>
                <a href="#quick-categories"><span class="quick-icon"><x-icon name="category" size="23"/></span><h3>دسته‌بندی‌ها</h3><p>انتخاب سریع مسیر موردنظر</p></a>
                <a href="javascript:void(0)" data-ai-open><span class="quick-icon"><x-icon name="ai" size="23"/></span><h3>مشاور هوشمند</h3><p>نیازت را توضیح بده و پیشنهاد بگیر</p></a>
                <a href="{{ auth()->check()?route('support.create'):route('login') }}"><span class="quick-icon"><x-icon name="support" size="23"/></span><h3>پشتیبانی</h3><p>پاسخ‌گویی و پیگیری درخواست</p></a>
            </div>
        </div>
        <div class="dj-slider">
            <div class="dj-slide active"><img src="{{ asset('Images/pdf.png') }}" alt="فایل PDF" width="700" height="510" fetchpriority="high"></div>
            <div class="dj-slide"><img src="{{ asset('Images/word.png') }}" alt="فایل Word" width="700" height="510" loading="lazy"></div>
            <div class="dj-slide"><img src="{{ asset('Images/excel.png') }}" alt="فایل Excel" width="700" height="510" loading="lazy"></div>
            <div class="dj-banner-copy"><span>فایل را قبل از خرید بررسی کن</span><strong>پیش‌نمایش + محتوای واقعی</strong></div>
            <div class="dj-slider-controls"><button type="button" data-dj-slide="prev" aria-label="قبلی"><x-icon name="arrow-right" size="18"/></button><button type="button" data-dj-slide="next" aria-label="بعدی"><x-icon name="arrow-left" size="18"/></button></div>
            <div class="dj-slider-dots"><i class="active"></i><i></i><i></i></div>
        </div>
    </section>

    <section class="dj-section container">
        <div class="dj-section-head"><div><span class="section-kicker">انتخاب هوشمند</span><h2>محبوب‌ترین فایل‌ها</h2><p>محصولات منتخب بر اساس داده‌های واقعی فروشگاه</p></div><a href="{{ route('products.index') }}">مشاهده همه <x-icon name="arrow-left" size="14"/></a></div>
        <div class="dj-products">
            @foreach($products as $product)
            <article class="dj-product"><a href="{{ route('product.show',$product) }}"><img src="{{ $product->thumbnail_url }}" alt="{{ $product->title }}" width="320" height="205" loading="lazy"><div class="dj-product-body"><div class="meta">{{ $product->category?->name ?: 'فایل دیجیتال' }} · #{{ $product->id }}</div><h3>{{ $product->title }}</h3><div class="price">{{ number_format($product->price) }} تومان</div></div></a><form method="POST" action="{{ route('cart.add',$product) }}">@csrf<button class="cart" aria-label="افزودن به سبد" title="افزودن به سبد"><x-icon name="cart" size="18"/></button></form></article>
            @endforeach
        </div>
    </section>

    <section class="dj-section dj-quick-category-section container" id="quick-categories">
        <div class="dj-category-heading"><span class="section-kicker">دسترسی سریع</span><h2>هر آنچه برای یادگیری و پیشرفت نیاز داری</h2><p>دسته موردنظر را انتخاب کنید و مستقیم سراغ فایل‌ها بروید.</p></div>
        <div class="premium-category-grid">
            @foreach($quickCategories as $category)
                @php($categoryUrl=$category['id']?route('search',['category'=>$category['id']]):route('search',['q'=>$category['title']]))
                <a class="premium-category-card accent-{{ $category['accent'] }}" href="{{ $categoryUrl }}" aria-label="مشاهده فایل‌های {{ $category['title'] }}">
                    <div class="premium-category-art"><img src="{{ asset('Images/'.$category['image']) }}" alt="تصویر {{ $category['title'] }}" width="600" height="450" loading="lazy"></div>
                    <div class="premium-category-content"><strong>{{ $category['title'] }}</strong><p>{{ $category['description'] }}</p><span class="premium-category-action">مشاهده فایل‌ها <x-icon name="arrow-left" size="15"/></span></div>
                </a>
            @endforeach
        </div>
    </section>

    @if($popularCategories->count())
    <section class="dj-section container">
        <div class="dj-section-head"><div><span class="section-kicker">محبوب‌ترین دسته‌ها</span><h2>از مسیرهای پرطرفدار شروع کن</h2><p>دسته‌هایی که بیشترین فایل منتشرشده را در فروشگاه دارند.</p></div></div>
        <div class="popular-category-strip">
            @foreach($popularCategories as $category)
                <a href="{{ route('search',['category'=>$category->id]) }}"><span class="popular-category-icon"><x-icon name="category" size="19"/></span><strong>{{ $category->name }}</strong><small>{{ number_format($category->published_products_count) }} فایل</small><x-icon name="arrow-left" size="14"/></a>
            @endforeach
        </div>
    </section>
    @endif

    <section class="dj-section container">
        <div class="dj-section-head"><div><span class="section-kicker">پرفروش‌ترین فایل‌ها</span><h2>انتخاب‌هایی که بیشتر خریده شده‌اند</h2><p>محصولات محبوب بر اساس تعداد خرید ثبت‌شده.</p></div><a href="{{ route('products.index',['sort'=>'popular']) }}">همه پرفروش‌ها <x-icon name="arrow-left" size="14"/></a></div>
        <div class="dj-products dj-products-compact">
            @foreach($bestSellingProducts as $product)
            <article class="dj-product"><a href="{{ route('product.show',$product) }}"><img src="{{ $product->thumbnail_url }}" alt="{{ $product->title }}" width="320" height="205" loading="lazy"><div class="dj-product-body"><div class="meta">پرفروش · {{ $product->category?->name ?: 'فایل دیجیتال' }}</div><h3>{{ $product->title }}</h3><div class="price">{{ number_format($product->price) }} تومان</div></div></a><form method="POST" action="{{ route('cart.add',$product) }}">@csrf<button class="cart" aria-label="افزودن به سبد"><x-icon name="cart" size="18"/></button></form></article>
            @endforeach
        </div>
    </section>

    <section class="container"><div class="dj-feature blue"><div><span>قبل از خرید مطمئن شو</span><h2>محتوای واقعی فایل را ببین</h2><p>پیش‌نمایش، اطلاعات فایل و پیشنهادهای مرتبط را یکجا ببین و با اطمینان انتخاب کن.</p><a class="dj-btn" href="{{ route('products.index') }}">مشاهده فروشگاه <x-icon name="arrow-left" size="16"/></a></div><div class="dj-feature-art"><img src="{{ asset('Images/pdf.png') }}" alt="پیش‌نمایش فایل" width="420" height="300" loading="lazy"></div><div></div></div></section>

    <section class="container"><div class="dj-feature dark"><div><span>برای فروشندگان</span><h2>فایل خودت را به فروش برسان</h2><p>فایل را ارسال کن؛ بررسی هوشمند، صف تأیید و مدیریت فروش در همان پنل یکپارچه انجام می‌شود.</p>@auth<a class="dj-btn" href="{{ route('account.dashboard') }}">ورود به پنل <x-icon name="arrow-left" size="16"/></a>@else<a class="dj-btn" href="{{ route('login') }}">شروع کار <x-icon name="arrow-left" size="16"/></a>@endauth</div><div class="dj-feature-art"><img src="{{ asset('Images/word.png') }}" alt="فروش فایل Word" width="420" height="300" loading="lazy"></div><div></div></div></section>

    <section class="dj-section container">
        <div class="dj-section-head"><div><span class="section-kicker">فایل‌های کاربردی</span><h2>برای کارهای روزمره آماده باش</h2><p>فایل‌هایی با توضیحات کامل و مناسب استفاده سریع.</p></div></div>
        <div class="dj-products dj-products-compact">
            @foreach($usefulProducts as $product)
            <article class="dj-product"><a href="{{ route('product.show',$product) }}"><img src="{{ $product->thumbnail_url }}" alt="{{ $product->title }}" width="320" height="205" loading="lazy"><div class="dj-product-body"><div class="meta">کاربردی · {{ $product->category?->name ?: 'فایل دیجیتال' }}</div><h3>{{ $product->title }}</h3><div class="price">{{ number_format($product->price) }} تومان</div></div></a><form method="POST" action="{{ route('cart.add',$product) }}">@csrf<button class="cart" aria-label="افزودن به سبد"><x-icon name="cart" size="18"/></button></form></article>
            @endforeach
        </div>
    </section>

    <section class="dj-section container">
        <div class="dj-section-head"><div><span class="section-kicker">جدیدترین فایل‌ها</span><h2>تازه‌ترین فایل‌های فروشگاه</h2><p>محصولات تازه منتشرشده را زودتر ببین.</p></div><a href="{{ route('products.index',['sort'=>'latest']) }}">مشاهده همه <x-icon name="arrow-left" size="14"/></a></div>
        <div class="dj-products dj-products-compact">
            @foreach($latestProducts as $product)
            <article class="dj-product"><a href="{{ route('product.show',$product) }}"><img src="{{ $product->thumbnail_url }}" alt="{{ $product->title }}" width="320" height="205" loading="lazy"><div class="dj-product-body"><div class="meta">جدید · {{ $product->category?->name ?: 'فایل دیجیتال' }}</div><h3>{{ $product->title }}</h3><div class="price">{{ number_format($product->price) }} تومان</div></div></a><form method="POST" action="{{ route('cart.add',$product) }}">@csrf<button class="cart" aria-label="افزودن به سبد"><x-icon name="cart" size="18"/></button></form></article>
            @endforeach
        </div>
    </section>

    @if($latestPosts->count())
    <section class="dj-section container"><div class="dj-section-head"><div><span class="section-kicker">راهنما</span><h2>آخرین مقالات</h2><p>محتوای آموزشی و راهنمای انتخاب فایل</p></div><a href="{{ route('blog.index') }}">همه مقالات <x-icon name="arrow-left" size="14"/></a></div><div class="dj-blog-grid">@foreach($latestPosts as $post)<a class="dj-blog-card" href="{{ route('blog.show',$post->slug) }}">@if($post->image)<img src="{{ str_starts_with($post->image,'http')?$post->image:asset($post->image) }}" alt="{{ $post->title }}" loading="lazy">@else<img src="{{ asset('Images/pdf.png') }}" alt="{{ $post->title }}" loading="lazy">@endif<h3>{{ $post->title }}</h3><p>{{ \Illuminate\Support\Str::limit(strip_tags($post->excerpt ?: $post->body),100) }}</p></a>@endforeach</div></section>
    @endif
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home-premium.css') }}">
@endpush

@push('scripts')
<script>document.addEventListener('DOMContentLoaded',()=>{const slides=[...document.querySelectorAll('.dj-slide')],dots=[...document.querySelectorAll('.dj-slider-dots i')];if(!slides.length)return;let i=0,t;const show=n=>{i=(n+slides.length)%slides.length;slides.forEach((s,k)=>s.classList.toggle('active',k===i));dots.forEach((d,k)=>d.classList.toggle('active',k===i))};const next=()=>show(i+1);document.querySelector('[data-dj-slide="next"]')?.addEventListener('click',next);document.querySelector('[data-dj-slide="prev"]')?.addEventListener('click',()=>show(i-1));t=setInterval(next,5500);document.querySelector('.dj-slider')?.addEventListener('mouseenter',()=>clearInterval(t));document.querySelector('.dj-slider')?.addEventListener('mouseleave',()=>t=setInterval(next,5500));});</script>
@endpush
