@extends('layouts.app')

@section('title', 'تسویه‌حساب | فایل‌مارکت')

@section('content')

<div class="container checkout-page">

    <div class="checkout-heading">

        <div>
            <span class="muted">مرحله نهایی خرید</span>

            <h1>تسویه‌حساب</h1>

            <p>
                سفارش خود را بررسی کنید و سپس پرداخت را انجام دهید.
            </p>
        </div>

    </div>

    @if(session('error'))
        <div class="checkout-message error">
            {{ session('error') }}
        </div>
    @endif

    @if(session('discount_error'))
        <div class="checkout-message error">
            {{ session('discount_error') }}
        </div>
    @endif

    @if(session('discount_success'))
        <div class="checkout-message success">
            {{ session('discount_success') }}
        </div>
    @endif

    <div class="checkout-grid">

        <section class="checkout-box">

            <div class="checkout-box-head">
                <div>
                    <span class="checkout-step">۱</span>
                    <div>
                        <h2>اطلاعات خریدار</h2>
                        <p>اطلاعات حساب شما</p>
                    </div>
                </div>
            </div>

            <div class="buyer-info">

                <div>
                    <span>نام</span>
                    <strong>
                        {{ auth()->user()->name }}
                    </strong>
                </div>

                <div>
                    <span>ایمیل</span>
                    <strong>
                        {{ auth()->user()->email }}
                    </strong>
                </div>

            </div>

            <div class="checkout-box-head products-head">

                <div>
                    <span class="checkout-step">۲</span>

                    <div>
                        <h2>محصولات سفارش</h2>
                        <p>{{ $products->count() }} فایل</p>
                    </div>
                </div>

            </div>

            <div class="checkout-products">

                @foreach($products as $product)

                    <div class="checkout-product">

                        <div class="checkout-product-info">

                            <div class="checkout-product-icon">
                                📄
                            </div>

                            <div>
                                <strong>
                                    {{ $product->title }}
                                </strong>

                                <span>
                                    محصول دیجیتال
                                </span>
                            </div>

                        </div>

                        <strong>
                            {{ number_format($product->price) }}
                            تومان
                        </strong>

                    </div>

                @endforeach

            </div>

        </section>

        <aside class="checkout-summary">

            <h2>خلاصه سفارش</h2>

            <div class="checkout-summary-row">
                <span>مجموع</span>

                <strong>
                    {{ number_format($subtotal) }}
                    تومان
                </strong>
            </div>

            <div class="checkout-discount">

                <div class="checkout-discount-title">
                    <strong>کد تخفیف</strong>
                </div>

                @if($discountCode)

                    <div class="discount-applied">

                        <div>
                            <span>کد فعال</span>

                            <strong>
                                {{ $discountCode }}
                            </strong>
                        </div>

                        <form
                            method="POST"
                            action="{{ route('checkout.discount.remove') }}"
                        >
                            @csrf
                            @method('DELETE')

                            <button type="submit">
                                حذف
                            </button>
                        </form>

                    </div>

                @else

                    <form
                        method="POST"
                        action="{{ route('checkout.discount') }}"
                        class="discount-form"
                    >

                        @csrf

                        <input
                            type="text"
                            name="code"
                            placeholder="مثلاً FREE100"
                            maxlength="50"
                            autocomplete="off"
                        >

                        <button type="submit">
                            اعمال
                        </button>

                    </form>

                @endif

            </div>

            <div class="checkout-summary-row discount-row">

                <span>تخفیف</span>

                <strong>
                    -{{ number_format($discount) }}
                    تومان
                </strong>

            </div>

            <div class="checkout-total">

                <span>مبلغ نهایی</span>

                <strong>
                    {{ number_format($total) }}
                    <small>تومان</small>
                </strong>

            </div>

            <form
                method="POST"
                action="{{ route('checkout.store') }}"
            >

                @csrf

                @if($total <= 0)

                    <button
                        type="submit"
                        class="checkout-submit free"
                    >
                        ثبت سفارش رایگان
                    </button>

                    <p class="checkout-note">
                        این سفارش بدون ورود به درگاه پرداخت ثبت خواهد شد.
                    </p>

                @else

                    <button
                        type="submit"
                        class="checkout-submit"
                    >
                        ادامه به پرداخت
                    </button>

                    <p class="checkout-note">
                        پس از ثبت سفارش به درگاه پرداخت منتقل می‌شوید.
                    </p>

                @endif

            </form>

        </aside>

    </div>

</div>

@endsection