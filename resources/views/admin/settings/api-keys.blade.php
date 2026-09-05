@extends('admin.layout')
@section('title','کلیدهای سرویس‌ها')
@section('content')
<div class="admin-page api-keys-page">
    <div class="admin-page-head">
        <div>
            <div class="admin-eyebrow">یکپارچه‌سازی‌ها</div>
            <h1>ثبت کلیدهای سرویس‌ها</h1>
            <p>کلیدها فقط به‌صورت رمزگذاری‌شده ذخیره می‌شوند و مقدار قبلی هر کلید در صفحه نمایش داده نمی‌شود.</p>
        </div>
    </div>

    @if(session('success'))<div class="admin-alert success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="admin-alert error">{{ $errors->first() }}</div>@endif

    <form method="POST" action="{{ route('admin.integrations.keys.update') }}" class="admin-form-card">
        @csrf
        @method('PUT')
        <div class="keys-grid">
            <fieldset>
                <legend>Google Gemini</legend>
                <p>کلید اصلی هوش مصنوعی سایت.</p>
                <label>API Key
                    <input name="gemini_key" type="password" dir="ltr" autocomplete="new-password" placeholder="{{ $keys['gemini'] ? 'کلید ثبت شده است؛ برای جایگزینی وارد کنید' : 'کلید Gemini را وارد کنید' }}">
                </label>
                <span class="key-state {{ $keys['gemini'] ? 'ok' : 'empty' }}">{{ $keys['gemini'] ? 'کلید ثبت شده است' : 'کلیدی ثبت نشده است' }}</span>
            </fieldset>

            <fieldset>
                <legend>GapGPT</legend>
                <p>ارائه‌دهنده پشتیبان هوش مصنوعی.</p>
                <label>API Key
                    <input name="gapgpt_key" type="password" dir="ltr" autocomplete="new-password" placeholder="{{ $keys['gapgpt'] ? 'کلید ثبت شده است؛ برای جایگزینی وارد کنید' : 'کلید GapGPT را وارد کنید' }}">
                </label>
                <span class="key-state {{ $keys['gapgpt'] ? 'ok' : 'empty' }}">{{ $keys['gapgpt'] ? 'کلید ثبت شده است' : 'کلیدی ثبت نشده است' }}</span>
            </fieldset>

            <fieldset>
                <legend>KPanel / IPPanel</legend>
                <p>کلید API ارسال پیامک و رمز یکبارمصرف.</p>
                <label>API Key
                    <input name="kpanel_key" type="password" dir="ltr" autocomplete="new-password" placeholder="{{ $keys['kpanel'] ? 'کلید ثبت شده است؛ برای جایگزینی وارد کنید' : 'کلید سرویس پیامک را وارد کنید' }}">
                </label>
                <span class="key-state {{ $keys['kpanel'] ? 'ok' : 'empty' }}">{{ $keys['kpanel'] ? 'کلید ثبت شده است' : 'کلیدی ثبت نشده است' }}</span>
            </fieldset>
        </div>
        <div class="keys-note">برای امنیت، هنگام ویرایش کلید فعلی را نشان نمی‌دهیم. اگر فیلدی خالی بماند، کلید قبلی حفظ می‌شود.</div>
        <button class="admin-primary-btn" type="submit">ذخیره کلیدها</button>
    </form>
</div>
@endsection
@push('styles')
<style>
.keys-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px}.keys-grid fieldset{border:1px solid #e7eaf0;border-radius:16px;padding:18px;background:#fff;min-width:0}.keys-grid legend{font-weight:900;padding:0 7px;color:#24324a}.keys-grid p{font-size:11px;line-height:1.9;color:#667085;min-height:42px;margin:0 0 12px}.keys-grid label{display:grid;gap:7px;font-size:11px;font-weight:800;color:#475467}.keys-grid input{width:100%;height:46px;border:1px solid #dfe3ea;border-radius:11px;padding:0 12px;font:inherit}.key-state{display:inline-flex;margin-top:10px;padding:5px 9px;border-radius:99px;font-size:9px;font-weight:800}.key-state.ok{background:#ecfdf3;color:#027a48}.key-state.empty{background:#f2f4f7;color:#667085}.keys-note{margin:16px 0;padding:12px 14px;border-radius:11px;background:#f8f9ff;color:#667085;font-size:10px;line-height:1.9}@media(max-width:900px){.keys-grid{grid-template-columns:1fr 1fr}}@media(max-width:600px){.keys-grid{grid-template-columns:1fr}}
</style>
@endpush