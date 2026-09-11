@php
$cart=session('cart',[]);
$cartProducts=empty($cart)?collect():\App\Models\Product::whereIn('id',array_keys($cart))->get();
$cartTotal=$cartProducts->sum(fn($product)=>$product->price*($cart[$product->id]??1));
$icon=function($path){return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.$path.'</svg>';};
$cartIcon=$icon('<path d="M4 5h2l1.4 9.2a2 2 0 0 0 2 1.7h7.9a2 2 0 0 0 1.9-1.4L21 8H7"/><circle cx="10" cy="19" r="1.2"/><circle cx="18" cy="19" r="1.2"/>');
$walletIcon=$icon('<path d="M4 7h15a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h12"/><path d="M16 12h3"/>');
$accountIcon=$icon('<circle cx="12" cy="8" r="3.2"/><path d="M5.5 20c.8-3.2 3.1-5 6.5-5s5.7 1.8 6.5 5"/>');
$searchIcon=$icon('<circle cx="11" cy="11" r="6.5"/><path d="m16 16 4 4"/>');
@endphp
<header class="header">
    <div class="container nav">
        <a class="logo" href="{{ route('home') }}">فایل‌مارکت</a>
        <nav aria-label="منوی اصلی"><ul class="menu">
            <li><a href="{{ route('home') }}">خانه</a></li>
            <li><a href="{{ route('products.index') }}">فروشگاه</a></li>
            <li><a href="#">دسته‌بندی‌ها</a></li>
            <li><a href="#">وبلاگ</a></li>
        </ul></nav>
        <div class="header-search">
            <form id="headerSearchForm" method="GET" action="{{ route('search') }}">
                <span class="header-search-mic" aria-hidden="true">♩</span>
                <input id="headerSearchInput" type="search" name="q" value="{{ request('q') }}" placeholder="هر فایل یا جزوه‌ای جستجو کن" autocomplete="off">
                <button type="submit" aria-label="جستجو">{!! $searchIcon !!}</button>
            </form>
        </div>
        <div class="nav-actions">
            <button class="cart-trigger" type="button" onclick="toggleCart()" aria-label="سبد خرید"><span>{!! $cartIcon !!}</span><span>سبد</span><b>{{ array_sum($cart) }}</b></button>
            @auth
                @include('partials.notification-center')
                <a class="wallet-header-link" href="{{ route('wallet.index') }}" aria-label="کیف پول"><span class="wallet-header-icon">{!! $walletIcon !!}</span><span>کیف پول</span>@php($headerWallet=auth()->user()->wallet)@if($headerWallet)<b>{{ number_format($headerWallet->balance) }}</b>@endif</a>
                <a class="account-header-link" href="{{ route('account.dashboard') }}" aria-label="حساب کاربری"><span>{!! $accountIcon !!}</span><span>حساب من</span></a>
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="login" type="submit">خروج</button></form>
            @else
                <a class="login" href="{{ route('login') }}">ورود</a><a class="register-link" href="{{ route('register') }}">ثبت‌نام</a>
            @endauth
        </div>
    </div>
</header>
<aside id="cart-panel" class="cart-panel" aria-label="سبد خرید">
    <div class="cart-head"><strong>سبد خرید</strong><button type="button" onclick="toggleCart()" aria-label="بستن">×</button></div>
    <div class="cart-items">
        @forelse($cartProducts as $product)<div class="mini-cart"><span>{{ $product->title }}</span><strong>{{ number_format($product->price*($cart[$product->id]??1)) }}</strong></div>
        @empty<div class="empty">سبد خرید خالی است.</div>@endforelse
    </div>
    @if($cartProducts->count())<div class="cart-total">مجموع: {{ number_format($cartTotal) }} تومان</div><a class="checkout-btn" href="{{ route('cart') }}">مشاهده سبد خرید</a>@endif
</aside>
<div id="cart-overlay" onclick="toggleCart()"></div>
@push('styles')<style>.header-search svg,.account-header-link svg,.cart-trigger svg,.wallet-header-icon svg{width:18px;height:18px;display:inline-block;vertical-align:middle}.account-header-link span:first-child{display:inline-flex;align-items:center}</style>@endpush