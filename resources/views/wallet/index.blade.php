@extends('layouts.app')

@section('title', 'کیف پول')

@section('content')

<div class="container wallet-page">

    <div class="wallet-head">

        <div>
            <span class="wallet-eyebrow">
                حساب کاربری
            </span>

            <h1>
                کیف پول من
            </h1>

            <p>
                موجودی و تراکنش‌های مالی شما
            </p>
        </div>

    </div>


    <section class="wallet-balance-card">

        <div class="wallet-icon">
            💳
        </div>

        <div>

            <span>
                موجودی فعلی
            </span>

            <strong>
                {{ number_format($wallet->balance) }}
            </strong>

            <small>
                تومان
            </small>

        </div>

    </section>


    <section class="wallet-topup-card">

        <h2>
            شارژ کیف پول
        </h2>

        <p>
            مبلغ موردنظر را انتخاب کنید.
        </p>


        <div class="wallet-amounts">

            @foreach([
                50000,
                100000,
                250000,
                500000,
                1000000
            ] as $amount)

                <button
                    type="button"
                    class="wallet-amount"
                    data-wallet-amount="{{ $amount }}"
                >
                    {{ number_format($amount) }}
                    تومان
                </button>

            @endforeach

        </div>


        <form
            method="POST"
            action="{{ route('wallet.topup') }}"
        >

            @csrf

            <label>
                مبلغ دلخواه
            </label>

            <input
                type="number"
                name="amount"
                id="wallet-amount-input"
                min="10000"
                max="50000000"
                step="1000"
                placeholder="مثلاً ۱۵۰۰۰۰"
                required
            >

            <button
                type="submit"
                class="wallet-topup-button"
            >
                شارژ کیف پول
            </button>

        </form>

    </section>


    <section class="wallet-transactions">

        <div class="wallet-section-head">

            <div>

                <span>
                    سوابق مالی
                </span>

                <h2>
                    تاریخچه تراکنش‌ها
                </h2>

            </div>

        </div>


        @forelse($transactions as $transaction)

            <article
                class="wallet-transaction
                {{ $transaction->type === 'credit'
                    ? 'credit'
                    : 'debit' }}"
            >

                <div class="transaction-icon">

                    @if($transaction->type === 'credit')
                        +
                    @else
                        −
                    @endif

                </div>

                <div class="transaction-info">

                    <strong>
                        {{ $transaction->description }}
                    </strong>

                    <small>
                        {{ optional($transaction->created_at)->format('Y/m/d H:i') }}
                    </small>

                </div>

                <div class="transaction-amount">

                    @if($transaction->type === 'credit')
                        +
                    @else
                        -
                    @endif

                    {{ number_format($transaction->amount) }}

                    <small>
                        تومان
                    </small>

                </div>

            </article>

        @empty

            <div class="wallet-empty">
                هنوز تراکنشی ثبت نشده است.
            </div>

        @endforelse


        <div class="wallet-pagination">
            {{ $transactions->links() }}
        </div>

    </section>

</div>

@push('scripts')

<script>
document
    .querySelectorAll('[data-wallet-amount]')
    .forEach(function (button) {

        button.addEventListener('click', function () {

            document.getElementById(
                'wallet-amount-input'
            ).value =
                this.dataset.walletAmount;

        });

    });
</script>

@endpush

@endsection