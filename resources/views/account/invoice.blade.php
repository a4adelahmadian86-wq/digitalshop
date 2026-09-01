@extends('layouts.app')

@section('title', 'فاکتور سفارش ' . $order->order_number)

@section('content')

<div class="container invoice-page">

    <div class="invoice-toolbar">

        <a
            href="{{ route('checkout.success', $order) }}"
        >
            بازگشت
        </a>

        <button
            type="button"
            onclick="window.print()"
        >
            چاپ / ذخیره PDF
        </button>

    </div>


    <article class="invoice-card">

        <header class="invoice-header">

            <div>

                <h1>
                    فاکتور فروش
                </h1>

                <p>
                    DigitalShop
                </p>

            </div>

            <div class="invoice-meta">

                <div>
                    شماره سفارش:
                    <strong>
                        {{ $order->order_number }}
                    </strong>
                </div>

                <div>
                    تاریخ:
                    {{ optional($order->paid_at)->format('Y/m/d H:i') }}
                </div>

            </div>

        </header>


        <section class="invoice-customer">

            <h2>
                اطلاعات خریدار
            </h2>

            <p>
                {{ auth()->user()->first_name }}
                {{ auth()->user()->last_name }}
            </p>

            <p>
                {{ auth()->user()->phone }}
            </p>

            @if(auth()->user()->email)
                <p>
                    {{ auth()->user()->email }}
                </p>
            @endif

        </section>


        <table class="invoice-table">

            <thead>

                <tr>
                    <th>محصول</th>
                    <th>تعداد</th>
                    <th>قیمت</th>
                    <th>جمع</th>
                </tr>

            </thead>

            <tbody>

            @foreach($order->items as $item)

                <tr>

                    <td>
                        {{ $item->product?->title ?? 'محصول' }}
                    </td>

                    <td>
                        {{ $item->quantity }}
                    </td>

                    <td>
                        {{ number_format($item->price) }}
                    </td>

                    <td>
                        {{ number_format(
                            $item->price * $item->quantity
                        ) }}
                    </td>

                </tr>

            @endforeach

            </tbody>

        </table>


        <section class="invoice-summary">

            <div>
                <span>جمع کالاها</span>
                <strong>
                    {{ number_format($order->subtotal) }}
                    تومان
                </strong>
            </div>

            <div>
                <span>تخفیف</span>
                <strong>
                    -
                    {{ number_format($order->discount) }}
                    تومان
                </strong>
            </div>

            <div class="invoice-total">
                <span>مبلغ پرداخت‌شده</span>
                <strong>
                    {{ number_format($order->total) }}
                    تومان
                </strong>
            </div>

        </section>


        <footer class="invoice-footer">

            <strong>
                پرداخت با موفقیت انجام شد.
            </strong>

            <span>
                این صفحه به‌عنوان فاکتور خرید شما قابل چاپ است.
            </span>

        </footer>

    </article>

</div>

@endsection