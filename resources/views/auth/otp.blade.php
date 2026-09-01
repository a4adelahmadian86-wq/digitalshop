@extends('layouts.app')

@section('title', 'تأیید شماره موبایل')

@section('content')

<div class="auth-page">

    <div class="auth-card">

        <div class="auth-header">

            <div class="auth-logo">
                DS
            </div>

            <h1>
                تأیید شماره موبایل
            </h1>

            <p>
                کد تأیید برای شماره
                <strong dir="ltr">
                    {{ $phone }}
                </strong>
                ارسال شد.
            </p>

        </div>


        @if($errors->any())

            <div class="auth-alert error">

                @foreach($errors->all() as $error)

                    <div>
                        {{ $error }}
                    </div>

                @endforeach

            </div>

        @endif


        @if(session('success'))

            <div class="auth-alert success">
                {{ session('success') }}
            </div>

        @endif


        @if(session('warning'))

            <div class="auth-alert warning">
                {{ session('warning') }}
            </div>

        @endif


        <form
            method="POST"
            action="{{ route('otp.verify') }}"
            class="auth-form"
        >

            @csrf

            <input
                type="hidden"
                name="phone"
                value="{{ $phone }}"
            >


            <div class="form-group">

                <label for="code">
                    کد تأیید
                </label>

                <input
                    id="code"
                    type="text"
                    name="code"
                    inputmode="numeric"
                    autocomplete="one-time-code"
                    maxlength="6"
                    minlength="6"
                    pattern="[0-9]{6}"
                    placeholder="123456"
                    required
                    autofocus
                    dir="ltr"
                    class="otp-input"
                >

                @error('code')

                    <small class="auth-error">
                        {{ $message }}
                    </small>

                @enderror

            </div>


            <div
                class="otp-timer"
                id="otpTimer"
                data-seconds="{{ $seconds ?? 120 }}"
            >

                امکان درخواست مجدد تا

                <strong id="timerValue">
                    {{ $seconds ?? 120 }}
                </strong>

                ثانیه

            </div>


            <button
                type="submit"
                class="auth-submit"
            >
                تأیید و ادامه
            </button>

        </form>


        <div
            id="resendBox"
            class="otp-resend"
            style="display:none;"
        >

            <p>
                کد را دریافت نکردید؟
            </p>

            <form
                method="POST"
                action="{{ route('otp.resend') }}"
            >

                @csrf

                <input
                    type="hidden"
                    name="phone"
                    value="{{ $phone }}"
                >

                <button
                    type="submit"
                    class="auth-link-button"
                >
                    ارسال مجدد کد
                </button>

            </form>

        </div>


        <div class="otp-warning">

            <strong>
                توجه امنیتی
            </strong>

            <p>
                برای حفظ امنیت حساب و جلوگیری از ارسال پیامک‌های ناخواسته،
                تعداد درخواست‌های کد تأیید محدود است.
            </p>

        </div>


        <div class="auth-register">

            <a href="{{ route('login') }}">
                بازگشت به ورود
            </a>

        </div>

    </div>

</div>

@endsection


@push('styles')

<style>

.otp-input {
    text-align: center;
    letter-spacing: 8px;
    font-size: 22px !important;
    font-weight: 700;
}

.otp-timer {
    text-align: center;
    color: #737b88;
    font-size: 13px;
    line-height: 1.8;
    margin-top: -5px;
}

.otp-timer strong {
    color: #111827;
    font-size: 18px;
}

.otp-resend {
    text-align: center;
    margin-top: 18px;
}

.otp-resend p {
    margin: 0 0 8px;
    color: #737b88;
    font-size: 13px;
}

.auth-link-button {
    border: 0;
    background: transparent;
    color: #536dfe;
    font-family: inherit;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
}

.otp-warning {
    margin-top: 24px;
    padding: 13px 14px;
    border-radius: 11px;
    background: #f8f9fb;
    border: 1px solid #e9ecf1;
    text-align: right;
}

.otp-warning strong {
    display: block;
    color: #3f4650;
    font-size: 12px;
    margin-bottom: 5px;
}

.otp-warning p {
    margin: 0;
    color: #737b88;
    font-size: 11px;
    line-height: 1.9;
}

.auth-alert.warning {
    background: #fff8e6;
    color: #8a5a00;
    border: 1px solid #f3dfaa;
}

</style>

@endpush


@push('scripts')

<script>

(function () {

    const timer = document.getElementById(
        'otpTimer'
    );

    const value = document.getElementById(
        'timerValue'
    );

    const resendBox = document.getElementById(
        'resendBox'
    );

    if (!timer || !value) {
        return;
    }

    let seconds = parseInt(
        timer.dataset.seconds || '120',
        10
    );

    function update() {

        value.textContent =
            Math.max(seconds, 0);

        if (seconds <= 0) {

            timer.style.display =
                'none';

            if (resendBox) {

                resendBox.style.display =
                    'block';

            }

            return;
        }

        seconds--;

        setTimeout(
            update,
            1000
        );
    }

    update();

})();

</script>

@endpush