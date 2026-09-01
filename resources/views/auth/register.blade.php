@extends('layouts.app')

@section('title', 'ثبت‌نام | فایل‌مارکت')

@section('content')

<div class="container auth-page">

    <div class="auth-box">

        <h1>
            ساخت حساب
        </h1>

        <p class="auth-subtitle">
            شماره موبایل شما با موفقیت تأیید شد.
        </p>


        <div class="verified-phone">
            شماره موبایل:
            <strong dir="ltr">
                {{ $phone }}
            </strong>
        </div>


        <form
            method="POST"
            action="{{ route('register.store') }}"
        >

            @csrf


            <label for="first_name">
                نام
            </label>

            <input
                id="first_name"
                class="form-input"
                name="first_name"
                value="{{ old('first_name') }}"
                autocomplete="given-name"
                required
            >


            <label for="last_name">
                نام خانوادگی
            </label>

            <input
                id="last_name"
                class="form-input"
                name="last_name"
                value="{{ old('last_name') }}"
                autocomplete="family-name"
                required
            >


            <label for="email">
                ایمیل
                <span class="optional">
                    (اختیاری)
                </span>
            </label>

            <input
                id="email"
                class="form-input"
                type="email"
                name="email"
                value="{{ old('email') }}"
                autocomplete="email"
            >


            <label for="password">
                رمز عبور
            </label>

            <input
                id="password"
                class="form-input"
                type="password"
                name="password"
                autocomplete="new-password"
                required
            >


            <label for="password_confirmation">
                تکرار رمز عبور
            </label>

            <input
                id="password_confirmation"
                class="form-input"
                type="password"
                name="password_confirmation"
                autocomplete="new-password"
                required
            >


            @foreach($errors->all() as $error)

                <div class="form-error">
                    {{ $error }}
                </div>

            @endforeach


            <button
                type="submit"
                class="buy-btn"
            >
                ساخت حساب
            </button>

        </form>


        <p>
            قبلاً حساب ساخته‌اید؟

            <a
                class="form-link"
                href="{{ route('login') }}"
            >
                ورود
            </a>
        </p>

    </div>

</div>

@endsection


@push('styles')

<style>

.auth-page {
    padding-top: 50px;
    padding-bottom: 50px;
}

.auth-box {
    width: 100%;
    max-width: 440px;
    margin: 0 auto;
    padding: 35px;
    box-sizing: border-box;
    background: #ffffff;
    border: 1px solid #e8ebf0;
    border-radius: 20px;
    box-shadow:
        0 18px 55px rgba(15, 23, 42, 0.08);
}

.auth-box h1 {
    margin: 0 0 8px;
    text-align: center;
    color: #111827;
    font-size: 25px;
    font-weight: 800;
}

.auth-subtitle {
    margin: 0 0 18px;
    text-align: center;
    color: #737b88;
    font-size: 13px;
    line-height: 1.8;
}

.verified-phone {
    margin-bottom: 22px;
    padding: 11px 13px;
    border-radius: 10px;
    background: #f0fdf4;
    color: #166534;
    font-size: 12px;
    line-height: 1.8;
    text-align: center;
}

.verified-phone strong {
    margin-right: 5px;
    letter-spacing: 1px;
}

.auth-box label {
    display: block;
    margin: 15px 0 7px;
    color: #303743;
    font-size: 13px;
    font-weight: 700;
}

.auth-box .form-input {
    width: 100%;
    height: 50px;
    box-sizing: border-box;
    padding: 0 13px;
    border: 1px solid #dfe3ea;
    border-radius: 11px;
    background: #ffffff;
    color: #171b23;
    font-family: inherit;
    font-size: 14px;
    outline: none;
    transition:
        border-color .18s ease,
        box-shadow .18s ease;
}

.auth-box .form-input:focus {
    border-color: #536dfe;
    box-shadow:
        0 0 0 4px rgba(83, 109, 254, .10);
}

.optional {
    color: #8a919d;
    font-size: 11px;
    font-weight: 400;
}

.form-error {
    margin-top: 10px;
    padding: 10px 12px;
    border-radius: 9px;
    background: #fff1f2;
    border: 1px solid #fecdd3;
    color: #be123c;
    font-size: 12px;
    line-height: 1.8;
}

.auth-box .buy-btn {
    width: 100%;
    height: 50px;
    margin-top: 22px;
    border: 0;
    border-radius: 11px;
    cursor: pointer;
}

.form-link {
    color: #536dfe;
    text-decoration: none;
    font-weight: 700;
}

.form-link:hover {
    text-decoration: underline;
}

@media (max-width: 520px) {

    .auth-box {
        padding: 26px 20px;
    }

}

</style>

@endpush