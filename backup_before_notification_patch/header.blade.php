@php
$cart = session('cart', []);
$cartProducts = empty($cart)
    ? collect()
    : \App\Models\Product::whereIn('id', array_keys($cart))->get();

$cartTotal = $cartProducts->sum('price');
@endphp

<header class="header">
<div class="container nav">

<a class="logo" href="{{ route('home') }}">فایل‌مارکت</a>

<nav>
<ul class="menu">
<li><a href="{{ route('home') }}">خانه</a></li>
<li><a href="{{ route('products.index') }}">فروشگاه</a></li>
<li><a href="#">دسته‌بندی‌ها</a></li>
<li><a href="#">وبلاگ</a></li>
</ul>
</nav>

<div class="nav-actions">

<button class="cart-trigger" onclick="toggleCart()">
<span>🛒</span>
<span>سبد</span>
<b>{{ array_sum($cart) }}</b>
</button>

@auth

<form method="POST" action="{{ route('logout') }}">
@csrf
<button class="login">خروج</button>
</form>

@else

<a class="login" href="{{ route('login') }}">
ورود
</a>

<a class="register-link" href="{{ route('register') }}">
ثبت‌نام
</a>

@endauth

</div>

</div>
</header>

<aside id="cart-panel" class="cart-panel">

<div class="cart-head">
<strong>سبد خرید</strong>
<button onclick="toggleCart()">×</button>
</div>

<div class="cart-items">

@forelse($cartProducts as $product)

<div class="mini-cart">
<span>{{ $product->title }}</span>
<strong>{{ number_format($product->price) }}</strong>
</div>

@empty

<div class="empty">سبد خرید خالی است.</div>

@endforelse

</div>

@if($cartProducts->count())

<div class="cart-total">
مجموع: {{ number_format($cartTotal) }} تومان
</div>

<a class="checkout-btn" href="{{ route('cart') }}">
مشاهده سبد خرید
</a>

@endif

</aside>

<div id="cart-overlay" onclick="toggleCart()"></div>