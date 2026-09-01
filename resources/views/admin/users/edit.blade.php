@extends('admin.layout')

@section('title', 'ویرایش کاربر')

@section('content')

<div class="admin-page narrow">

```
<div class="admin-page-head">

    <div>

        <div class="admin-eyebrow">
            کاربران
        </div>

        <h1>
            ویرایش کاربر
        </h1>

        <p>
            {{ $user->phone }}
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
        action="{{ route('admin.users.update', $user) }}"
        class="admin-form"
    >

        @csrf
        @method('PUT')


        <div class="form-row">

            <div class="form-group">

                <label>
                    نام
                </label>

                <input
                    type="text"
                    name="first_name"
                    value="{{ old('first_name', $user->first_name) }}"
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
                    value="{{ old('last_name', $user->last_name) }}"
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
                value="{{ old('phone', $user->phone) }}"
                inputmode="numeric"
                maxlength="11"
                dir="ltr"
                required
            >

        </div>


        <div class="form-group">

            <label>
                نقش
            </label>

            <select
                name="role"
                required
            >

                <option
                    value="user"
                    @selected(old('role', $user->role) === 'user')
                >
                    کاربر
                </option>

                <option
                    value="admin"
                    @selected(old('role', $user->role) === 'admin')
                >
                    مدیر
                </option>

            </select>

        </div>


        <div class="form-row">

            <div class="form-group">

                <label>
                    رمز عبور جدید
                </label>

                <div class="password-field">

                    <input
                        type="password"
                        name="password"
                        id="edit-password"
                        minlength="8"
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        onclick="togglePassword('edit-password', this)"
                        aria-label="نمایش رمز عبور"
                    >
                        👁
                    </button>

                </div>

                <small>
                    اگر نمی‌خواهید تغییر کند، خالی بگذارید.
                </small>

            </div>


            <div class="form-group">

                <label>
                    تکرار رمز عبور جدید
                </label>

                <div class="password-field">

                    <input
                        type="password"
                        name="password_confirmation"
                        id="edit-password-confirmation"
                        minlength="8"
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        onclick="togglePassword('edit-password-confirmation', this)"
                        aria-label="نمایش رمز عبور"
                    >
                        👁
                    </button>

                </div>

            </div>

        </div>


        <div class="discount-used-info">

            وضعیت:

            @if($user->is_active)
                <strong>فعال</strong>
            @else
                <strong>غیرفعال</strong>
            @endif

            <br>

            تأیید موبایل:

            @if($user->phone_verified_at)
                <strong>تأیید شده</strong>
            @else
                <strong>تأیید نشده</strong>
            @endif

        </div>


        <div class="form-actions">

            <a
                href="{{ route('admin.users.index') }}"
                class="admin-secondary-btn"
            >
                بازگشت
            </a>

            <button
                type="submit"
                class="admin-primary-btn"
            >
                ذخیره تغییرات
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

        button.setAttribute(
            'aria-label',
            'مخفی کردن رمز عبور'
        );

    } else {

        input.type = 'password';

        button.textContent = '👁';

        button.setAttribute(
            'aria-label',
            'نمایش رمز عبور'
        );
    }
}
</script>

@endsection
