@extends('admin.layout')

@section('title', 'افزودن کاربر')

@section('content')

<div class="admin-page narrow">

```
<div class="admin-page-head">

    <div>

        <div class="admin-eyebrow">
            کاربران
        </div>

        <h1>
            افزودن کاربر
        </h1>

        <p>
            ایجاد حساب کاربری جدید توسط مدیر
        </p>

    </div>

</div>


@if($errors->any())

    <div class="admin-alert error">

        <ul style="margin:0;padding-right:20px;">

            @foreach($errors->all() as $error)

                <li>
                    {{ $error }}
                </li>

            @endforeach

        </ul>

    </div>

@endif


<div class="admin-form-card">

    <form
        method="POST"
        action="{{ route('admin.users.store') }}"
        class="admin-form"
    >

        @csrf


        <div class="form-row">

            <div class="form-group">

                <label>
                    نام
                </label>

                <input
                    type="text"
                    name="first_name"
                    value="{{ old('first_name') }}"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    نام خانوادگی
                </label>

                <input
                    type="text"
                    name="last_name"
                    value="{{ old('last_name') }}"
                >

            </div>

        </div>


        <div class="form-group">

            <label>
                شماره موبایل
            </label>

            <input
                type="tel"
                name="phone"
                value="{{ old('phone') }}"
                inputmode="numeric"
                maxlength="11"
                placeholder="09123456789"
                dir="ltr"
                required
            >

        </div>


        <div class="form-row">

            <div class="form-group">

                <label>
                    رمز عبور
                </label>

                <div class="password-field">

                    <input
                        type="password"
                        name="password"
                        id="create-password"
                        minlength="8"
                        required
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        onclick="togglePassword('create-password', this)"
                        aria-label="نمایش رمز عبور"
                    >
                        👁
                    </button>

                </div>

                <small>
                    حداقل ۸ کاراکتر
                </small>

            </div>


            <div class="form-group">

                <label>
                    تکرار رمز عبور
                </label>

                <div class="password-field">

                    <input
                        type="password"
                        name="password_confirmation"
                        id="create-password-confirmation"
                        minlength="8"
                        required
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        onclick="togglePassword('create-password-confirmation', this)"
                        aria-label="نمایش تکرار رمز عبور"
                    >
                        👁
                    </button>

                </div>

            </div>

        </div>


        <div class="form-group">

            <label>
                نقش
            </label>

            <select name="role" required>

                <option
                    value="user"
                    @selected(old('role', 'user') === 'user')
                >
                    کاربر
                </option>

                <option
                    value="admin"
                    @selected(old('role') === 'admin')
                >
                    مدیر
                </option>

            </select>

        </div>


        <div class="discount-used-info">

            کاربری که از این بخش ساخته می‌شود
            نیاز به عبور از OTP برای ایجاد حساب توسط مدیر ندارد.

        </div>


        <div class="form-actions">

            <a
                href="{{ route('admin.users.index') }}"
                class="admin-secondary-btn"
            >
                انصراف
            </a>

            <button
                type="submit"
                class="admin-primary-btn"
            >
                ایجاد کاربر
            </button>

        </div>

    </form>

</div>
```

</div>

<script>
function togglePassword(id, button) {

    const input = document.getElementById(id);

    if (input.type === 'password') {

        input.type = 'text';

        button.textContent = '🙈';

    } else {

        input.type = 'password';

        button.textContent = '👁';

    }
}
</script>

@endsection
