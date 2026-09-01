
<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>احراز هویت مدیر</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

<div class="admin-login">

    <div class="admin-login-box">

        <div class="admin-logo">◆</div>

        <h1>احراز هویت مدیر</h1>

        <p>نام کاربری و رمز عبور را وارد کنید.</p>

        @error('login')
            <div class="admin-error">{{ $message }}</div>
        @enderror

        <form method="POST" action="{{ route('admin.login.store') }}">
            @csrf

            <input
                type="text"
                name="username"
                placeholder="نام کاربری"
                required
            >

            <input
                type="password"
                name="password"
                placeholder="رمز عبور"
                required
            >

            <button type="submit">
                ورود به پنل
            </button>
        </form>

    </div>

</div>

</body>
</html>س