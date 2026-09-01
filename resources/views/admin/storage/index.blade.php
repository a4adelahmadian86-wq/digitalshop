@extends('admin.layout')

@section('title', 'Storage')

@section('content')

<div class="admin-page">

    <div class="admin-page-head">

        <div>

            <div class="admin-eyebrow">
                Storage Management
            </div>

            <h1>
                Storage
            </h1>

            <p>
                مدیریت محل ذخیره فایل‌های محصولات
            </p>

        </div>

        <a
            href="{{ route('admin.storage.create') }}"
            class="admin-primary-btn"
        >
            + افزودن Provider
        </a>

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

    <section class="admin-table-card">

        <div class="admin-table-wrap">

            <table class="admin-table">

                <thead>

                    <tr>
                        <th>نام</th>
                        <th>نوع</th>
                        <th>محصولات</th>
                        <th>وضعیت</th>
                        <th>پیش‌فرض</th>
                        <th>عملیات</th>
                    </tr>

                </thead>

                <tbody>

                @forelse($providers as $provider)

                    <tr>

                        <td>
                            <strong>
                                {{ $provider->name }}
                            </strong>
                        </td>

                        <td>
                            {{ strtoupper($provider->type) }}
                        </td>

                        <td>
                            {{ $provider->products_count }}
                        </td>

                        <td>

                            @if($provider->is_active)

                                <span class="status-badge active">
                                    فعال
                                </span>

                            @else

                                <span class="status-badge inactive">
                                    غیرفعال
                                </span>

                            @endif

                        </td>

                        <td>

                            @if($provider->is_default)

                                <span class="status-badge active">
                                    پیش‌فرض
                                </span>

                            @else

                                —
                                
                            @endif

                        </td>

                        <td>

                            <div class="admin-actions">

                                <form
                                    method="POST"
                                    action="{{ route('admin.storage.test', $provider) }}"
                                >

                                    @csrf

                                    <button
                                        class="action-btn toggle"
                                        type="submit"
                                    >
                                        تست اتصال
                                    </button>

                                </form>

                                @if(!$provider->is_default)

                                    <form
                                        method="POST"
                                        action="{{ route('admin.storage.default', $provider) }}"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        <button
                                            class="action-btn edit"
                                            type="submit"
                                        >
                                            پیش‌فرض
                                        </button>

                                    </form>

                                @endif

                                <a
                                    href="{{ route('admin.storage.edit', $provider) }}"
                                    class="action-btn edit"
                                >
                                    ویرایش
                                </a>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="6"
                            class="admin-empty"
                        >
                            هیچ Storage Provider ثبت نشده است.
                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </section>

</div>

@endsection