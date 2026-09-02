@php
$cart = session('cart', []);
$cartProducts = empty($cart)
    ? collect()
    : \App\Models\Product::whereIn('id', array_keys($cart))->get();
$cartTotal = $cartProducts->sum('price');
$navCategories = \App\Models\Category::where('is_active', 1)
    ->orderBy('sort_order')->take(12)->get();
@endphp

<header class="site-header">
    <div class="topbar">
        <div class="container topbar-inner">
            <span>خرید آسان، دانلود سریع و دسترسی همیشگی به فایل‌ها</span>
            <div class="topbar-links">
                <a href="{{ route('products.index') }}">همه محصولات</a>
                <span>پشتیبانی</span>
            </div>
        </div>
    </div>

    <div class="header-main">
        <div class="container header-inner">
            <button class="mobile-menu-toggle" type="button" aria-label="باز کردن منو" onclick="document.body.classList.toggle('nav-open')">☰</button>

            <a class="brand" href="{{ route('home') }}">
                <span class="brand-mark">ف</span>
                <span>
                    <strong>فایل‌مارکت</strong>
                    <small>بازار فایل‌های دیجیتال</small>
                </span>
            </a>

            <nav class="main-nav" aria-label="منوی اصلی">
                <a class="nav-link active" href="{{ route('home') }}">خانه</a>
                <a class="nav-link" href="{{ route('products.index') }}">فروشگاه</a>
                <div class="nav-dropdown">
                    <button class="nav-link nav-dropdown-trigger" type="button">دسته‌بندی‌ها <span>⌄</span></button>
                    <div class="category-mega">
                        <div class="mega-title">
                            <strong>دسته‌بندی محصولات</strong>
                            <a href="{{ route('products.index') }}">مشاهده همه ←</a>
                        </div>
                        <div class="mega-grid">
                            @foreach($navCategories as $category)
                                <a href="{{ route('products.index') }}" class="mega-item">
                                    <span class="mega-icon">◈</span>
                                    <span>
                                        <strong>{{ $category->name }}</strong>
                                        <small>مشاهده فایل‌ها</small>
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <a class="nav-link" href="{{ route('products.index') }}">فایل‌های جدید</a>
            </nav>

            <form class="header-search" method="GET" action="{{ route('search') }}">
                <span>⌕</span>
                <input name="q" value="{{ request('q') }}" placeholder="چه فایلی نیاز داری؟" aria-label="جستجوی محصولات">
                <button type="submit">جستجو</button>
            </form>

            <div class="header-actions">
                <button class="cart-trigger" type="button" onclick="toggleCart()" aria-label="سبد خرید">
                    <span class="header-action-icon">🛒</span>
                    <span class="header-action-label">سبد خرید</span>
                    @if(array_sum($cart))<b>{{ array_sum($cart) }}</b>@endif
                </button>

                @auth
                    @include('partials.notification-center')
                    <a class="account-trigger" href="{{ route('account.dashboard') }}" aria-label="حساب کاربری">
                        <span class="header-action-icon">◉</span>
                        <span class="header-action-label">حساب من</span>
                    </a>
                @else
                    <a class="account-trigger" href="{{ route('login') }}">
                        <span class="header-action-icon">◉</span>
                        <span class="header-action-label">ورود / ثبت‌نام</span>
                    </a>
                @endauth
            </div>
        </div>
    </div>
</header>

<aside id="cart-panel" class="cart-panel">
    <div class="cart-head">
        <div><strong>سبد خرید</strong><small>{{ array_sum($cart) }} کالا</small></div>
        <button type="button" onclick="toggleCart()">×</button>
    </div>
    <div class="cart-items">
        @forelse($cartProducts as $product)
            <div class="mini-cart">
                <div class="mini-cart-thumb">{{ $product->thumbnail ? '' : '📁' }}</div>
                <div class="mini-cart-info">
                    <span>{{ $product->title }}</span>
                    <strong>{{ number_format($product->price) }} تومان</strong>
                </div>
            </div>
        @empty
            <div class="empty-cart">
                <span>🛒</span>
                <strong>سبد خریدت خالی است</strong>
                <small>محصول مورد نیازت را پیدا کن.</small>
            </div>
        @endforelse
    </div>
    @if($cartProducts->count())
        <div class="cart-total"><span>مجموع</span><strong>{{ number_format($cartTotal) }} تومان</strong></div>
        <a class="checkout-btn" href="{{ route('cart') }}">مشاهده سبد خرید</a>
    @else
        <a class="checkout-btn secondary" href="{{ route('products.index') }}">رفتن به فروشگاه</a>
    @endif
</aside>
<div id="cart-overlay" onclick="toggleCart()"></div>