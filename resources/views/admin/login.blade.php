
<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>ورود مدیریت</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body class="login-page">

<form method="POST"
      action="{{ route('admin.login.store') }}"
      class="login-box">

    @csrf

    <h1>ورود مدیریت</h1>

    <p>مدیریت DigitalShop</p>

    @if($errors->any())
        <div class="alert error">
            {{ $errors->first() }}
        </div>
    @endif

    <input
        name="username"
        placeholder="نام کاربری"
        value="{{ old('username') }}"
        required
    >

    <input
        type="password"
        name="password"
        placeholder="رمز عبور"
        required
    >

    <button>ورود به پنل</button>

</form>

</body>
</html>