@extends('layouts.app')

@section('title', 'روش پرداخت')

@section('content')

<div class="container">

    <div class="checkout-payment-page">

        <h1>
            انتخاب روش پرداخت
        </h1>

        <p>
            روش پرداخت موردنظر خود را انتخاب کنید.
        </p>

        <div class="payment-summary">

            <div>
                مبلغ کالاها:
                <strong>
                    {{ number_format($subtotal) }}
                    تومان
                </strong>
            </div>

            <div>
                تخفیف:
                <strong>
                    {{ number_format($discount) }}
                    تومان
                </strong>
            </div>

            <div>
                مالیات:
                <strong>
                    {{ number_format($tax) }}
                    تومان
                </strong>
            </div>

            <hr>

            <div>
                مبلغ نهایی:
                <strong>
                    {{ number_format($total) }}
                    تومان
                </strong>
            </div>

            <div>
                موجودی کیف پول:
                <strong>
                    {{ number_format($walletBalance) }}
                    تومان
                </strong>
            </div>

        </div>


        <form
            method="POST"
            action="{{ route('checkout.payment.store') }}"
        >

            @csrf

            @if($walletBalance >= $total)

                <label class="payment-option">

                    <input
                        type="radio"
                        name="method"
                        value="wallet"
                        checked
                    >

                    <span>
                        پرداخت کامل از کیف پول
                    </span>

                </label>


                <label class="payment-option">

                    <input
                        type="radio"
                        name="method"
                        value="gateway"
                    >

                    <span>
                        پرداخت کامل از درگاه
                    </span>

                </label>

            @elseif($walletBalance > 0)

                <label class="payment-option">

                    <input
                        type="radio"
                        name="method"
                        value="wallet_gateway"
                        checked
                    >

                    <span>

                        کیف پول + درگاه

                        <small>
                            {{ number_format($walletBalance) }}
                            تومان از کیف پول
                            و
                            {{ number_format($total - $walletBalance) }}
                            تومان از درگاه
                        </small>

                    </span>

                </label>


                <label class="payment-option">

                    <input
                        type="radio"
                        name="method"
                        value="gateway"
                    >

                    <span>
                        پرداخت کامل از درگاه
                    </span>

                </label>

            @endif


            <button
                type="submit"
                class="checkout-btn"
            >
                ادامه پرداخت
            </button>

        </form>

    </div>

</div>

@endsection