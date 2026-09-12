@extends('layouts.app')

@section('title', 'کیف پول | فایل‌مارکت')
@section('description', 'مدیریت موجودی، شارژ و تاریخچه تراکنش‌های کیف پول')

@section('content')
<div class="container wallet-page">

    <div class="wallet-head">
        <div>
            <span class="wallet-eyebrow">حساب کاربری</span>
            <h1>کیف پول من</h1>
            <p>موجودی قابل استفاده، شارژ، مصرف و ردپای کامل تراکنش‌ها</p>
        </div>
        <a class="btn secondary" href="{{ route('account.dashboard') }}">بازگشت به داشبورد</a>
    </div>

    @if(session('success'))
        <div class="wallet-alert success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="wallet-alert error">{{ session('error') }}</div>
    @endif

    <div class="wallet-stats">
        <article class="wallet-stat primary">
            <small>موجودی فعلی</small>
            <strong>{{ number_format($stats['balance']) }} <em>تومان</em></strong>
        </article>
        <article class="wallet-stat">
            <small>مجموع شارژها</small>
            <strong>{{ number_format($stats['total_credit']) }} <em>تومان</em></strong>
        </article>
        <article class="wallet-stat">
            <small>مجموع مصرف</small>
            <strong>{{ number_format($stats['total_debit']) }} <em>تومان</em></strong>
        </article>
        <article class="wallet-stat">
            <small>در انتظار تأیید</small>
            <strong>{{ number_format($stats['pending_topups']) }} <em>تومان</em></strong>
        </article>
    </div>

    <div class="wallet-layout">
        <section class="wallet-topup-card">
            <h2>شارژ کیف پول</h2>
            <p>مبلغ پیشنهادی را انتخاب کنید یا مبلغ دلخواه وارد کنید. حداقل ۱۰ هزار تومان.</p>

            <div class="wallet-amounts">
                @foreach($suggested as $amount)
                    <button type="button" class="wallet-amount" data-wallet-amount="{{ $amount }}">
                        {{ number_format($amount) }} تومان
                    </button>
                @endforeach
            </div>

            <form method="POST" action="{{ route('wallet.topup') }}" class="wallet-topup-form">
                @csrf
                <label for="wallet-amount-input">مبلغ دلخواه (تومان)</label>
                <input
                    type="number"
                    name="amount"
                    id="wallet-amount-input"
                    min="10000"
                    max="50000000"
                    step="1000"
                    value="{{ old('amount') }}"
                    placeholder="مثلاً ۱۵۰۰۰۰"
                    required
                >
                @error('amount')
                    <div class="wallet-field-error">{{ $message }}</div>
                @enderror
                <button type="submit" class="wallet-topup-button">ادامه و پرداخت امن</button>
            </form>

            <ul class="wallet-notes">
                <li>پس از پرداخت موفق، موجودی بلافاصله اعمال می‌شود.</li>
                <li>در صورت قطع اتصال، وضعیت از روی درگاه و سابقه شارژ کنترل می‌شود.</li>
                <li>از دوباره‌کلیک روی دکمه پرداخت خودداری کنید.</li>
            </ul>
        </section>

        <section class="wallet-transactions">
            <div class="wallet-section-head">
                <div>
                    <span>سوابق مالی</span>
                    <h2>تاریخچه تراکنش‌ها</h2>
                </div>
                <form method="GET" class="wallet-filters">
                    <select name="type" onchange="this.form.submit()">
                        <option value="">همه انواع</option>
                        <option value="credit" @selected(request('type')==='credit')>افزایش</option>
                        <option value="debit" @selected(request('type')==='debit')>کاهش</option>
                    </select>
                </form>
            </div>

            @forelse($transactions as $transaction)
                <article class="wallet-transaction {{ $transaction->type }}">
                    <div class="transaction-icon">{{ $transaction->type === 'credit' ? '+' : '−' }}</div>
                    <div class="transaction-info">
                        <strong>{{ $transaction->description }}</strong>
                        <small>
                            {{ optional($transaction->created_at)->format('Y/m/d H:i') }}
                            · وضعیت: {{ $transaction->status }}
                            @if($transaction->balance_after !== null)
                                · موجودی بعد: {{ number_format($transaction->balance_after) }}
                            @endif
                        </small>
                    </div>
                    <div class="transaction-amount">
                        {{ $transaction->type === 'credit' ? '+' : '-' }}{{ number_format($transaction->amount) }}
                        <small>تومان</small>
                    </div>
                </article>
            @empty
                <div class="wallet-empty">هنوز تراکنشی ثبت نشده است.</div>
            @endforelse

            <div class="wallet-pagination">{{ $transactions->links() }}</div>
        </section>
    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('[data-wallet-amount]').forEach(function (button) {
    button.addEventListener('click', function () {
        document.getElementById('wallet-amount-input').value = this.dataset.walletAmount;
    });
});
</script>
@endpush
@endsection
