@extends('layouts.app')

@section('title', 'فراموشی رمز عبور')

@section('content')

<div class="auth-page">

    <div class="auth-card">

        <div class="auth-header">

            <div class="auth-logo">
                DS
            </div>

            <h1>
                فراموشی رمز عبور
            </h1>

            <p>
                شماره موبایل خود را وارد کنید تا کد تأیید برای شما ارسال شود.
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

        <form
            method="POST"
            action="{{ route('password.send') }}"
            class="auth-form"
        >

            @csrf

            <div class="form-group">

                <label for="phone">
                    شماره موبایل
                </label>

                <input
                    id="phone"
                    type="tel"
                    name="phone"
                    value="{{ old('phone') }}"
                    inputmode="numeric"
                    autocomplete="tel"
                    placeholder="09123456789"
                    maxlength="11"
                    required
                    autofocus
                    dir="ltr"
                >

                @error('phone')

                    <small class="auth-error">
                        {{ $message }}
                    </small>

                @enderror

            </div>

            <button
                type="submit"
                class="auth-submit"
            >
                ارسال کد تأیید
            </button>

        </form>

        <div class="auth-register">

            <a href="{{ route('login') }}">
                بازگشت به ورود
            </a>

        </div>

    </div>

</div>

@endsection