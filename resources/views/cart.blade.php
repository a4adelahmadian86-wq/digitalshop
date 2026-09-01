@extends('layouts.app')

@section('title','سبد خرید | فایل‌مارکت')

@section('description','بررسی سبد خرید و فایل‌های انتخاب شده')

@section('content')

<div class="container cart-page">

<h1>سبد خرید</h1>

@if(session('cart_error'))
<div class="alert-error">
{{ session('cart_error') }}
</div>
@endif

@if($products->isEmpty())

<div class="cart-box empty">
سبد خرید شما خالی است.
</div>

@else

<div class="cart-layout">

<div>

<div class="cart-box">

<div class="cart-title">
<strong>فایل‌های انتخاب‌شده</strong>
<span>{{ $products->count() }} فایل</span>
</div>

@foreach($products as $product)

<div class="cart-row">

<a class="cart-product"
href="{{ route('product.show',$product) }}">

<div class="cart-thumb">📁</div>

<div>
<strong>{{ $product->title }}</strong>

<div class="muted">
فایل دیجیتال
</div>
</div>

</a>

<div class="cart-price">
{{ number_format($product->price) }}
تومان
</div>

<div class="cart-actions">

<form method="POST"
action="{{ route('cart.later',$product) }}">
@csrf
<button title="بعداً می‌خرم">
♡
</button>
</form>

<form method="POST"
action="{{ route('cart.remove',$product) }}">
@csrf
<button title="حذف">
×
</button>
</form>

</div>

</div>

@endforeach

</div>


{{-- پیشنهادات --}}

<section class="cart-box recommendations">

<h2>
{{ collect([
'پیشنهادات برای شما',
'به سبد خرید شما می‌خورد',
'شاید این‌ها را هم بپسندید',
'محصولات پیشنهادی ما'
])->random() }}
</h2>

<div class="mini-products">

@foreach(\App\Models\Product::where('is_published',1)->whereNotIn('id',$products->pluck('id'))->latest()->take(4)->get() as $suggestion)

<a href="{{ route('product.show',$suggestion) }}"
class="mini-product">

<span>📁</span>

<div>
<strong>{{ $suggestion->title }}</strong>
<small>
{{ number_format($suggestion->price) }} تومان
</small>
</div>

</a>

@endforeach

</div>

</section>


{{-- اخیراً دیده شده --}}

<section class="cart-box recent-box">

<h2>اخیراً مشاهده کرده‌اید</h2>

<div class="mini-products">

@foreach(\App\Models\Product::whereIn('id',session('recent_products', []))->where('is_published',1)->take(4)->get() as $recent)

<a href="{{ route('product.show',$recent) }}"
class="mini-product">

<span>◷</span>

<div>
<strong>{{ $recent->title }}</strong>
<small>
{{ number_format($recent->price) }} تومان
</small>
</div>

</a>

@endforeach

</div>

</section>

</div>


{{-- خلاصه خرید --}}

<aside class="cart-summary-box">

<h2>خلاصه سبد خرید</h2>


<div class="summary-line">
<span>مجموع قیمت فایل‌ها</span>

<strong>
{{ number_format($subtotal ?? $total) }}
تومان
</strong>
</div>


{{-- کد تخفیف --}}

<div class="discount-box">

    <h3>کد تخفیف</h3>

    @if(session('discount_code'))

        <div class="discount-active">

            کد
            <strong>
                {{ session('discount_code') }}
            </strong>
            اعمال شده است.

            <form method="POST"
                  action="{{ route('cart.discount.remove') }}">

                @csrf

                @method('DELETE')

                <button type="submit">
                    حذف کد
                </button>

            </form>

        </div>

    @else

        <form method="POST"
              action="{{ route('cart.discount') }}">

            @csrf

            <input
                type="text"
                name="code"
                placeholder="کد تخفیف">

            <button type="submit">
                اعمال
            </button>

        </form>

    @endif


    @if(session('discount_error'))

        <div class="cart-message error">
            {{ session('discount_error') }}
        </div>

    @endif


    @if(session('discount_success'))

        <div class="cart-message success">
            {{ session('discount_success') }}
        </div>

    @endif

</div>


<div class="cart-summary-row">

    <span>مجموع فایل‌ها</span>

    <strong>
        {{ number_format($subtotal ?? $total) }}
        تومان
    </strong>

</div>


<div class="cart-summary-row">

    <span>تخفیف</span>

    <strong>
        -{{ number_format($discount ?? 0) }}
        تومان
    </strong>

</div>


<div class="summary-line saving">

    <span>سود شما از این خرید</span>

    <strong>
        {{ number_format($discount ?? 0) }}
        تومان
    </strong>

</div>


<hr>


<div class="cart-summary-total">

    <span>مبلغ نهایی</span>

    <strong>
        {{ number_format($total) }}
        تومان
    </strong>

</div>


<div class="summary-total">

    <span>مجموع سبد خرید</span>

    <strong>
        {{ number_format($total) }}
        تومان
    </strong>

</div>


<a class="checkout-btn"
   href="{{ route('checkout') }}">

    ادامه و تسویه‌حساب

</a>


<p class="summary-note">

پرداخت امن و دریافت فایل پس از تأیید پرداخت

</p>

</aside>

</div>

@endif


{{-- سبد خرید بعدی --}}

@if($laterProducts->count())

<section class="cart-box later-box">

<div class="cart-title">

<h2>سبد خرید بعدی</h2>

<span>
{{ $laterProducts->count() }} فایل
</span>

</div>

@foreach($laterProducts as $product)

<div class="later-row">

<div>

<strong>
{{ $product->title }}
</strong>

<div class="muted">
{{ number_format($product->price) }} تومان
</div>

</div>

<form method="POST"
      action="{{ route('cart.move',$product) }}">

@csrf

<button class="later-btn">
افزودن به سبد
</button>

</form>

</div>

@endforeach

</section>

@endif

</div>

@endsection