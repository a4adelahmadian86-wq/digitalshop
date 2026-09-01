@extends('admin.layout')

@section('title', 'کدهای تخفیف')

@section('content')

<div class="admin-page">

    <div class="admin-page-head">

        <div>
            <div class="admin-eyebrow">فروشگاه</div>

            <h1>کدهای تخفیف</h1>

            <p>
                مدیریت کدهای تخفیف و محدودیت استفاده
            </p>
        </div>

        <a
            href="{{ route('admin.discounts.create') }}"
            class="admin-primary-btn"
        >
            + ایجاد کد تخفیف
        </a>

    </div>

    @if(session('success'))
        <div class="admin-alert success">
            {{ session('success') }}
        </div>
    @endif

    <div class="admin-stat-grid">

        <div class="admin-stat-card">
            <span>کل کدها</span>
            <strong>{{ $codes->count() }}</strong>
        </div>

        <div class="admin-stat-card">
            <span>کدهای فعال</span>
            <strong>
                {{ $codes->where('is_active', true)->count() }}
            </strong>
        </div>

        <div class="admin-stat-card">
            <span>کدهای غیرفعال</span>
            <strong>
                {{ $codes->where('is_active', false)->count() }}
            </strong>
        </div>

        <div class="admin-stat-card">
            <span>مجموع استفاده</span>
            <strong>
                {{ $codes->sum('used_count') }}
            </strong>
        </div>

    </div>

    <div class="admin-table-card">

        <div class="admin-table-head">
            <h2>لیست کدهای تخفیف</h2>
        </div>

        @if($codes->count())

            <div class="admin-table-wrap">

                <table class="admin-table">

                    <thead>
                        <tr>
                            <th>کد</th>
                            <th>مقدار</th>
                            <th>استفاده</th>
                            <th>شروع</th>
                            <th>انقضا</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>

                    <tbody>

                    @foreach($codes as $code)

                        @php
                            $expired =
                                $code->expires_at &&
                                $code->expires_at->isPast();

                            $notStarted =
                                $code->starts_at &&
                                $code->starts_at->isFuture();

                            $available =
                                $code->is_active &&
                                !$expired &&
                                !$notStarted &&
                                (
                                    !$code->max_uses ||
                                    $code->used_count < $code->max_uses
                                );
                        @endphp

                        <tr>

                            <td>
                                <span class="discount-code">
                                    {{ $code->code }}
                                </span>
                            </td>

                            <td>
                                <strong>
                                    {{ rtrim(rtrim(number_format($code->amount, 2), '0'), '.') }}
                                    {{ $code->is_percent ? '%' : 'تومان' }}
                                </strong>
                            </td>

                            <td>
                                {{ $code->used_count }}

                                <span class="muted">
                                    /
                                    {{ $code->max_uses ?? '∞' }}
                                </span>
                            </td>

                            <td>
                                {{ $code->starts_at
                                    ? $code->starts_at->format('Y/m/d H:i')
                                    : '—'
                                }}
                            </td>

                            <td>
                                {{ $code->expires_at
                                    ? $code->expires_at->format('Y/m/d H:i')
                                    : 'بدون انقضا'
                                }}
                            </td>

                            <td>

                                @if(!$code->is_active)

                                    <span class="status-badge inactive">
                                        غیرفعال
                                    </span>

                                @elseif($expired)

                                    <span class="status-badge danger">
                                        منقضی
                                    </span>

                                @elseif($notStarted)

                                    <span class="status-badge warning">
                                        هنوز شروع نشده
                                    </span>

                                @elseif(!$available)

                                    <span class="status-badge danger">
                                        تکمیل ظرفیت
                                    </span>

                                @else

                                    <span class="status-badge active">
                                        فعال
                                    </span>

                                @endif

                            </td>

                            <td>

                                <div class="admin-actions">

                                    <a
                                        href="{{ route('admin.discounts.edit', $code) }}"
                                        class="action-btn edit"
                                    >
                                        ویرایش
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('admin.discounts.toggle', $code) }}"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="action-btn toggle"
                                        >
                                            {{ $code->is_active ? 'غیرفعال' : 'فعال' }}
                                        </button>

                                    </form>

                                    <form
                                        method="POST"
                                        action="{{ route('admin.discounts.destroy', $code) }}"
                                        onsubmit="return confirm('این کد تخفیف حذف شود؟')"
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

                                </div>

                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="admin-empty">

                <div class="admin-empty-icon">%</div>

                <h3>هنوز کد تخفیفی ایجاد نشده است.</h3>

                <p>
                    اولین کد تخفیف فروشگاه را ایجاد کنید.
                </p>

                <a
                    href="{{ route('admin.discounts.create') }}"
                    class="admin-primary-btn"
                >
                    ایجاد کد تخفیف
                </a>

            </div>

        @endif

    </div>

</div>

@endsection