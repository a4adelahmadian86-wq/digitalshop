@extends('admin.layout')

@section('title', 'کاربران')

@section('content')

<div class="admin-page">

```
<div class="admin-page-head">

    <div>

        <div class="admin-eyebrow">
            مدیریت کاربران
        </div>

        <h1>
            کاربران
        </h1>

        <p>
            مدیریت حساب‌های کاربری فروشگاه
        </p>

    </div>

    <a
        href="{{ route('admin.users.create') }}"
        class="admin-primary-btn"
    >
        + افزودن کاربر
    </a>

</div>


{{-- Stats --}}

<div class="admin-stat-grid">

    <div class="admin-stat-card">

        <div class="admin-stat-top">

            <span class="admin-stat-label">
                کل کاربران
            </span>

            <div class="admin-stat-icon">
                👥
            </div>

        </div>

        <div class="admin-stat-value">

            <strong>
                {{ number_format($stats['total']) }}
            </strong>

            <small>
                نفر
            </small>

        </div>

    </div>


    <div class="admin-stat-card">

        <div class="admin-stat-top">

            <span class="admin-stat-label">
                کاربران فعال
            </span>

            <div class="admin-stat-icon">
                ✓
            </div>

        </div>

        <div class="admin-stat-value">

            <strong>
                {{ number_format($stats['active']) }}
            </strong>

            <small>
                فعال
            </small>

        </div>

    </div>


    <div class="admin-stat-card">

        <div class="admin-stat-top">

            <span class="admin-stat-label">
                تأیید شده
            </span>

            <div class="admin-stat-icon">
                ✓
            </div>

        </div>

        <div class="admin-stat-value">

            <strong>
                {{ number_format($stats['verified']) }}
            </strong>

            <small>
                موبایل
            </small>

        </div>

    </div>


    <div class="admin-stat-card">

        <div class="admin-stat-top">

            <span class="admin-stat-label">
                مدیران
            </span>

            <div class="admin-stat-icon">
                ★
            </div>

        </div>

        <div class="admin-stat-value">

            <strong>
                {{ number_format($stats['admins']) }}
            </strong>

            <small>
                مدیر
            </small>

        </div>

    </div>

</div>


@if(session('success'))

    <div class="admin-alert success">
        {{ session('success') }}
    </div>

@endif


@if(session('error'))

    <div class="admin-alert error">
        {{ session('error') }}
    </div>

@endif


@if($errors->any())

    <div class="admin-alert error">

        {{ $errors->first() }}

    </div>

@endif


{{-- Filters --}}

<section class="admin-box" style="margin-bottom:18px;">

    <form
        method="GET"
        action="{{ route('admin.users.index') }}"
    >

        <div class="form-row">

            <div class="form-group">

                <label>
                    جست‌وجو
                </label>

                <input
                    type="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="نام یا شماره موبایل"
                >

            </div>


            <div class="form-group">

                <label>
                    نقش
                </label>

                <select name="role">

                    <option value="">
                        همه نقش‌ها
                    </option>

                    <option
                        value="user"
                        @selected(request('role') === 'user')
                    >
                        کاربر
                    </option>

                    <option
                        value="admin"
                        @selected(request('role') === 'admin')
                    >
                        مدیر
                    </option>

                </select>

            </div>


            <div class="form-group">

                <label>
                    وضعیت
                </label>

                <select name="status">

                    <option value="">
                        همه وضعیت‌ها
                    </option>

                    <option
                        value="active"
                        @selected(request('status') === 'active')
                    >
                        فعال
                    </option>

                    <option
                        value="inactive"
                        @selected(request('status') === 'inactive')
                    >
                        غیرفعال
                    </option>

                </select>

            </div>

        </div>


        <div class="form-actions">

            <a
                href="{{ route('admin.users.index') }}"
                class="admin-secondary-btn"
            >
                پاک کردن
            </a>

            <button
                type="submit"
                class="admin-primary-btn"
            >
                جست‌وجو
            </button>

        </div>

    </form>

</section>


{{-- Users table --}}

<section class="admin-table-card">

    <div class="admin-table-wrap">

        <table class="admin-table">

            <thead>

                <tr>

                    <th>
                        کاربر
                    </th>

                    <th>
                        موبایل
                    </th>

                    <th>
                        نقش
                    </th>

                    <th>
                        وضعیت
                    </th>

                    <th>
                        تأیید موبایل
                    </th>

                    <th>
                        عضویت
                    </th>

                    <th>
                        عملیات
                    </th>

                </tr>

            </thead>


            <tbody>

            @forelse($users as $user)

                <tr>

                    <td>

                        <strong>

                            {{ trim(
                                ($user->first_name ?? '') .
                                ' ' .
                                ($user->last_name ?? '')
                            ) ?: 'بدون نام' }}

                        </strong>

                    </td>


                    <td dir="ltr">

                        {{ $user->phone }}

                    </td>


                    <td>

                        @if($user->role === 'admin')

                            <span class="status-badge active">
                                مدیر
                            </span>

                        @else

                            <span class="status-badge inactive">
                                کاربر
                            </span>

                        @endif

                    </td>


                    <td>

                        @if($user->is_active)

                            <span class="status-badge active">
                                فعال
                            </span>

                        @else

                            <span class="status-badge danger">
                                غیرفعال
                            </span>

                        @endif

                    </td>


                    <td>

                        @if($user->phone_verified_at)

                            <span class="status-badge active">
                                تأیید شده
                            </span>

                        @else

                            <span class="status-badge inactive">
                                تأیید نشده
                            </span>

                        @endif

                    </td>


                    <td>

                        <span class="muted">

                            {{ optional($user->created_at)->format('Y/m/d') }}

                        </span>

                    </td>


                    <td>

                        <div class="admin-actions">

                            <a
                                href="{{ route('admin.users.show', $user) }}"
                                class="action-btn"
                            >
                                مشاهده
                            </a>

                            <a
                                href="{{ route('admin.users.edit', $user) }}"
                                class="action-btn"
                            >
                                ویرایش
                            </a>


                            @if(auth()->id() !== $user->id)

                                <form
                                    method="POST"
                                    action="{{ route('admin.users.toggle', $user) }}"
                                >

                                    @csrf
                                    @method('PATCH')

                                    <button
                                        type="submit"
                                        class="action-btn {{ $user->is_active ? 'delete' : '' }}"
                                    >

                                        {{ $user->is_active
                                            ? 'غیرفعال'
                                            : 'فعال'
                                        }}

                                    </button>

                                </form>

                            @endif

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="7"
                        class="admin-empty"
                    >
                        کاربری با این مشخصات پیدا نشد.
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</section>


<div style="margin-top:20px;">

    {{ $users->links() }}

</div>
```

</div>

@endsection
