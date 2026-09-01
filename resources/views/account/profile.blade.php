@extends('account.layout')
@section('title','پروفایل')
@section('content')
<div class="account-head"><div><small>حساب کاربری</small><h1>پروفایل من</h1><p>اطلاعات شخصی خود را مدیریت کنید. شماره موبایل شناسه حساب شماست.</p></div></div>
<section class="account-card"><form method="POST" action="{{ route('account.profile.update') }}" class="account-form">@csrf @method('PUT')<div class="form-grid"><label>نام<input name="first_name" value="{{ old('first_name',$user->first_name) }}" required></label><label>نام خانوادگی<input name="last_name" value="{{ old('last_name',$user->last_name) }}"></label><label>شماره موبایل<input value="{{ $user->phone }}" dir="ltr" disabled></label><label>کد ملی<input name="national_code" value="{{ old('national_code',$user->national_code) }}" inputmode="numeric" maxlength="10" dir="ltr" placeholder="اختیاری"></label></div><div class="form-note">شماره موبایل از این بخش قابل تغییر نیست؛ تغییر آن باید از فرآیند امن تأیید شماره انجام شود.</div><button class="btn primary">ذخیره تغییرات</button></form></section>
@endsection
