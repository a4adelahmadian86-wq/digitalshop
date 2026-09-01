<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>ورود مدیریت</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

<div class="admin-login">

    <div class="admin-login-box">

        <div class="admin-logo">◆</div>

        <h1>ورود مدیریت</h1>

        <p>شماره همراه مدیر را وارد کنید.</p>

        @error('phone')
            <div class="admin-error">{{ $message }}</div>
        @enderror

        <form method="POST" action="{{ route('admin.phone.check') }}">
            @csrf

            <input
                type="tel"
                name="phone"
                inputmode="numeric"
                pattern="[0-9]*"
                maxlength="11"
                placeholder="09151234567"
                required
            >

            <button type="submit">
                ادامه
            </button>
        </form>

    </div>

</div>

</body>
</html>