@extends('account.layout')
@section('title','امنیت حساب')
@section('content')
<div class="account-head"><div><small>امنیت</small><h1>امنیت حساب</h1><p>رمز عبور حساب خود را مدیریت کنید.</p></div></div>
<section class="account-card"><form method="POST" action="{{ route('account.security.update') }}" class="account-form narrow-form">@csrf @method('PUT')<label>رمز عبور فعلی<input type="password" name="current_password" autocomplete="current-password" required></label><label>رمز عبور جدید<input type="password" name="password" minlength="8" autocomplete="new-password" required></label><label>تکرار رمز عبور جدید<input type="password" name="password_confirmation" minlength="8" autocomplete="new-password" required></label><div class="form-note">رمز عبور باید حداقل ۸ کاراکتر باشد.</div><button class="btn primary">تغییر رمز عبور</button></form></section>
@endsection
