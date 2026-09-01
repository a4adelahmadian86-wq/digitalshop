@extends('admin.layout')

@section('title', 'دسته‌بندی‌ها')

@section('content')

<div class="admin-page">

    <div class="admin-page-head">

        <div>
            <div class="admin-eyebrow">
                مدیریت فروشگاه
            </div>

            <h1>
                دسته‌بندی‌ها
            </h1>

            <p>
                مدیریت دسته‌بندی محصولات فروشگاه
            </p>
        </div>

        <a
            href="{{ route('admin.categories.create') }}"
            class="admin-primary-btn"
        >
            + افزودن دسته‌بندی
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


    @if($categories->isEmpty())

        <section class="admin-box admin-empty">

            <div class="admin-empty-icon">
                ◈
            </div>

            <h2>
                هنوز دسته‌بندی‌ای ایجاد نشده است
            </h2>

            <p>
                برای شروع اولین دسته‌بندی فروشگاه را ایجاد کنید.
            </p>

            <a
                href="{{ route('admin.categories.create') }}"
                class="admin-primary-btn"
            >
                افزودن دسته‌بندی
            </a>

        </section>

    @else

        <section class="admin-table-card">

            <div class="admin-table-head">

                <h2>
                    فهرست دسته‌بندی‌ها
                </h2>

            </div>

            <div class="admin-table-wrap">

                <table class="admin-table">

                    <thead>

                        <tr>
                            <th>نام</th>
                            <th>Slug</th>
                            <th>محصولات</th>
                            <th>ترتیب</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>

                    </thead>

                    <tbody>

                    @foreach($categories as $category)

                        <tr>

                            <td>
                                <strong>
                                    {{ $category->name }}
                                </strong>
                            </td>

                            <td>
                                <span class="discount-code">
                                    {{ $category->slug }}
                                </span>
                            </td>

                            <td>
                                {{ number_format($category->products_count) }}
                            </td>

                            <td>
                                {{ number_format($category->sort_order) }}
                            </td>

                            <td>

                                @if($category->is_active)

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

                                <div class="admin-actions">

                                    <a
                                        href="{{ route('admin.categories.edit', $category) }}"
                                        class="action-btn edit"
                                    >
                                        ویرایش
                                    </a>


                                    <form
                                        method="POST"
                                        action="{{ route('admin.categories.toggle', $category) }}"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="action-btn toggle"
                                        >
                                            {{ $category->is_active
                                                ? 'غیرفعال کردن'
                                                : 'فعال کردن'
                                            }}
                                        </button>

                                    </form>


                                    @if($category->products_count === 0)

                                        <form
                                            method="POST"
                                            action="{{ route('admin.categories.destroy', $category) }}"
                                            onsubmit="return confirm('آیا از حذف این دسته‌بندی مطمئن هستید؟');"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="action-btn delete"
                                            >
                                                حذف
                                            </button>

                                        </form>

                                    @else

                                        <span
                                            class="status-badge warning"
                                            title="این دسته‌بندی دارای محصول است"
                                        >
                                            دارای محصول
                                        </span>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

        </section>

    @endif

</div>

@endsection