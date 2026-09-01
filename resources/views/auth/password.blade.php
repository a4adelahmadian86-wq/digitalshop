@extends('layouts.app')

@section('title', 'ورود به حساب')

@section('content')

<div class="auth-page">

    <div class="auth-card">

        <div class="auth-header">

            <div class="auth-logo">
                DS
            </div>

            <h1>
                ورود به حساب کاربری
            </h1>

            <p>
                رمز عبور حساب خود را وارد کنید.
            </p>

        </div>


        <div
            id="passwordAlert"
            class="auth-alert error"
            style="{{ $errors->any() ? 'display:block;' : 'display:none;' }}"
        >

            @if($errors->any())

                {{ $errors->first() }}

            @endif

        </div>


        <form
            method="POST"
            action="{{ route('login.password.store') }}"
            class="auth-form"
        >

            @csrf


            <div class="form-group">

                <label>
                    شماره موبایل
                </label>

                <input
                    type="tel"
                    value="{{ $phone }}"
                    readonly
                    dir="ltr"
                    class="readonly-input"
                >

                <input
                    type="hidden"
                    name="phone"
                    value="{{ $phone }}"
                >

            </div>


            <div class="form-group">

                <div class="form-label-row">

                    <label for="password">
                        رمز عبور
                    </label>

                    <a
                        href="{{ route('password.request') }}"
                        class="auth-forgot"
                    >
                        فراموشی رمز عبور
                    </a>

                </div>


                <div class="password-wrap">

                    <input
                        id="password"
                        type="password"
                        name="password"
                        autocomplete="current-password"
                        placeholder="رمز عبور"
                        dir="ltr"
                        required
                        autofocus
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        onclick="togglePassword()"
                        aria-label="نمایش رمز عبور"
                    >

                        <svg
                            id="eyeIcon"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                        >
                            <path
                                d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"
                            ></path>

                            <circle
                                cx="12"
                                cy="12"
                                r="3"
                            ></circle>
                        </svg>

                    </button>

                </div>

            </div>


            <label class="remember-row">

                <input
                    type="checkbox"
                    name="remember"
                    value="1"
                >

                <span>
                    مرا به خاطر بسپار
                </span>

            </label>


            <button
                type="submit"
                class="auth-submit"
            >
                ورود
            </button>

        </form>


        <div class="auth-back">

            <a href="{{ route('login') }}">
                تغییر شماره موبایل
            </a>

        </div>

    </div>

</div>

@endsection


@push('styles')

<style>

.auth-page {
    min-height: calc(100vh - 140px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 50px 20px;
}

.auth-card {
    width: 100%;
    max-width: 460px;
    background: #fff;
    border: 1px solid #e7eaf0;
    border-radius: 20px;
    padding: 34px;
    box-sizing: border-box;
    box-shadow: 0 15px 50px rgba(20, 30, 50, .08);
}

.auth-header {
    text-align: center;
    margin-bottom: 28px;
}

.auth-logo {
    width: 58px;
    height: 58px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 18px;
    background: #111827;
    color: #fff;
    font-weight: 800;
    font-size: 20px;
}

.auth-header h1 {
    margin: 0 0 9px;
    font-size: 25px;
}

.auth-header p {
    margin: 0;
    color: #737b88;
    font-size: 14px;
}

.auth-alert {
    border-radius: 11px;
    padding: 12px 14px;
    margin-bottom: 20px;
    font-size: 14px;
    line-height: 1.8;
}

.auth-alert.error {
    background: #fff1f1;
    color: #b42318;
    border: 1px solid #ffd4d1;
}

.auth-form {
    display: flex;
    flex-direction: column;
    gap: 19px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group label {
    font-size: 14px;
    font-weight: 700;
}

.form-label-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.auth-forgot {
    color: #536dfe;
    font-size: 12px;
    text-decoration: none;
}

.form-group input {
    width: 100%;
    height: 48px;
    border: 1px solid #dfe3ea;
    border-radius: 11px;
    padding: 0 14px;
    box-sizing: border-box;
    font-family: inherit;
    font-size: 14px;
    outline: none;
}

.form-group input:focus {
    border-color: #536dfe;
    box-shadow: 0 0 0 3px rgba(83,109,254,.08);
}

.readonly-input {
    background: #f7f8fa !important;
    color: #555d68;
}

.password-wrap {
    position: relative;
}

.password-wrap input {
    padding-left: 52px !important;
}

.password-toggle {
    position: absolute;
    left: 10px;
    top: 9px;
    width: 30px;
    height: 30px;
    border: 0;
    background: transparent;
    padding: 4px;
    cursor: pointer;
}

.password-toggle svg {
    width: 22px;
    height: 22px;
    fill: none;
    stroke: #737b88;
    stroke-width: 1.7;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.password-toggle:hover svg {
    stroke: #536dfe;
}

.remember-row {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #59616d;
    font-size: 13px;
    cursor: pointer;
}

.remember-row input {
    width: 16px;
    height: 16px;
}

.auth-submit {
    width: 100%;
    height: 50px;
    border: 0;
    border-radius: 11px;
    background: #111827;
    color: #fff;
    font-family: inherit;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
}

.auth-back {
    text-align: center;
    margin-top: 20px;
}

.auth-back a {
    color: #536dfe;
    font-size: 13px;
    text-decoration: none;
}

@media (max-width: 600px) {

    .auth-page {
        padding: 25px 14px;
    }

    .auth-card {
        padding: 25px 20px;
    }

    .form-label-row {
        gap: 8px;
    }

}

</style>

@endpush


@push('scripts')

<script>

function togglePassword() {

    const input =
        document.getElementById('password');

    const icon =
        document.getElementById('eyeIcon');

    if (!input || !icon) {
        return;
    }

    if (
        input.type === 'password'
    ) {

        input.type = 'text';

        icon.innerHTML = `
            <path d="M3 3l18 18"></path>
            <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"></path>
            <path d="M9.9 5.2A10.8 10.8 0 0 1 12 5c6.5 0 10 7 10 7a18.4 18.4 0 0 1-3.1 3.9"></path>
            <path d="M6.1 6.1C3.4 8 2 12 2 12s3.5 7 10 7c1.6 0 3-.4 4.2-1"></path>
        `;

    } else {

        input.type = 'password';

        icon.innerHTML = `
            <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"></path>
            <circle cx="12" cy="12" r="3"></circle>
        `;

    }

}

</script>

@endpush