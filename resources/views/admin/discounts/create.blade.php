@extends('admin.layout')

@section('title', 'ایجاد کد تخفیف')

@section('content')

<div class="admin-page narrow">

    <div class="admin-page-head">

        <div>
            <div class="admin-eyebrow">تخفیف</div>

            <h1>ایجاد کد تخفیف</h1>

            <p>
                یک کد جدید برای کاربران فروشگاه ایجاد کنید.
            </p>
        </div>

        <a
            href="{{ route('admin.discounts.index') }}"
            class="admin-secondary-btn"
        >
            بازگشت
        </a>

    </div>

    <div class="admin-form-card">

        @if($errors->any())
            <div class="admin-alert error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('admin.discounts.store') }}"
            class="admin-form"
        >

            @csrf

            <div class="form-row">

                <div class="form-group">

                    <label>کد تخفیف</label>

                    <input
                        type="text"
                        name="code"
                        value="{{ old('code') }}"
                        placeholder="FREE100"
                        maxlength="50"
                        required
                    >

                    <small>
                        فقط حروف انگلیسی، عدد، - و _ مجاز است.
                    </small>

                </div>

                <div class="form-group">

                    <label>مقدار تخفیف</label>

                    <input
                        type="number"
                        name="amount"
                        value="{{ old('amount') }}"
                        min="0"
                        step="0.01"
                        required
                    >

                </div>

            </div>

            <div class="form-group">

                <label class="checkbox-label">

                    <input
                        type="checkbox"
                        name="is_percent"
                        value="1"
                        {{ old('is_percent') ? 'checked' : '' }}
                    >

                    تخفیف درصدی است

                </label>

            </div>

            <div class="form-row">

                <div class="form-group">

                    <label>حداکثر تعداد استفاده</label>

                    <input
                        type="number"
                        name="max_uses"
                        value="{{ old('max_uses') }}"
                        min="1"
                        placeholder="بدون محدودیت"
                    >

                </div>

                <div class="form-group">

                    <label>شروع اعتبار</label>

                    <input
                        type="datetime-local"
                        name="starts_at"
                        value="{{ old('starts_at') }}"
                    >

                </div>

            </div>

            <div class="form-group">

                <label>پایان اعتبار</label>

                <input
                    type="datetime-local"
                    name="expires_at"
                    value="{{ old('expires_at') }}"
                >

            </div>

            <div class="form-actions">

                <a
                    href="{{ route('admin.discounts.index') }}"
                    class="admin-secondary-btn"
                >
                    انصراف
                </a>

                <button
                    type="submit"
                    class="admin-primary-btn"
                >
                    ذخیره کد تخفیف
                </button>

            </div>

        </form>

    </div>

</div>

@endsection