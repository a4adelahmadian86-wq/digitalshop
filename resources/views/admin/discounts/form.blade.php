
@extends('admin.layout')

@section('title', 'کد تخفیف جدید')

@section('content')

<div class="panel form-panel">

    <h1>ساخت کد تخفیف</h1>

    <form method="POST"
          action="{{ route('admin.discounts.store') }}">

        @csrf

        <label>کد</label>
        <input name="code"
               value="{{ old('code') }}"
               placeholder="مثلاً FREE100"
               required>

        <label>مقدار تخفیف</label>
        <input name="amount"
               type="number"
               min="0"
               value="{{ old('amount') }}"
               required>

        <label class="check">
            <input type="checkbox"
                   name="is_percent"
                   value="1">
            درصدی است
        </label>

        <label>حداکثر استفاده</label>
        <input name="max_uses"
               type="number"
               min="1">

        <label>تاریخ انقضا</label>
        <input name="expires_at"
               type="datetime-local">

        @if($errors->any())
            <div class="alert error">
                {{ $errors->first() }}
            </div>
        @endif

        <button class="primary">ذخیره کد</button>

    </form>

</div>

@endsection