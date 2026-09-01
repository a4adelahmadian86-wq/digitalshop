
@extends('admin.layout')

@section('title', 'ویرایش کد تخفیف')

@section('content')

<div class="admin-page narrow">

    <div class="admin-page-head">

        <div>
            <div class="admin-eyebrow">تخفیف</div>

            <h1>ویرایش {{ $discount->code }}</h1>

            <p>
                تنظیمات و محدودیت‌های این کد را تغییر دهید.
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
            action="{{ route('admin.discounts.update', $discount) }}"
            class="admin-form"
        >

            @csrf
            @method('PUT')

            <div class="form-row">

                <div class="form-group">

                    <label>کد تخفیف</label>

                    <input
                        type="text"
                        name="code"
                        value="{{ old('code', $discount->code) }}"
                        maxlength="50"
                        required
                    >

                </div>

                <div class="form-group">

                    <label>مقدار</label>

                    <input
                        type="number"
                        name="amount"
                        value="{{ old('amount', $discount->amount) }}"
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
                        {{ old('is_percent', $discount->is_percent) ? 'checked' : '' }}
                    >

                    تخفیف درصدی است

                </label>

            </div>

            <div class="form-row">

                <div class="form-group">

                    <label>حداکثر استفاده</label>

                    <input
                        type="number"
                        name="max_uses"
                        value="{{ old('max_uses', $discount->max_uses) }}"
                        min="1"
                        placeholder="بدون محدودیت"
                    >

                </div>

                <div class="form-group">

                    <label>شروع اعتبار</label>

                    <input
                        type="datetime-local"
                        name="starts_at"
                        value="{{ old('starts_at', $discount->starts_at?->format('Y-m-d\TH:i')) }}"
                    >

                </div>

            </div>

            <div class="form-group">

                <label>پایان اعتبار</label>

                <input
                    type="datetime-local"
                    name="expires_at"
                    value="{{ old('expires_at', $discount->expires_at?->format('Y-m-d\TH:i')) }}"
                >

            </div>

            <div class="form-group">

                <label class="checkbox-label">

                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        {{ old('is_active', $discount->is_active) ? 'checked' : '' }}
                    >

                    کد فعال باشد

                </label>

            </div>

            <div class="discount-used-info">

                استفاده فعلی:
                <strong>{{ $discount->used_count }}</strong>

                @if($discount->max_uses)
                    از {{ $discount->max_uses }}
                @endif

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
                    ذخیره تغییرات
                </button>

            </div>

        </form>

    </div>

</div>

@endsection