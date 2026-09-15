@extends('admin.layout')

@section('title', 'افزودن کاربر')

@section('content')
<div class="admin-page narrow">
    <div class="admin-page-head"><div><div class="admin-eyebrow">کاربران</div><h1>افزودن کاربر</h1><p>ایجاد حساب جدید توسط مدیر ارشد.</p></div></div>
    @if($errors->any())<div class="admin-alert error"><ul style="margin:0;padding-right:20px">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <div class="admin-form-card">
        <form method="POST" action="{{ route('admin.users.store') }}" class="admin-form">
            @csrf
            <div class="form-row"><div class="form-group"><label>نام</label><input type="text" name="first_name" value="{{ old('first_name') }}" required></div><div class="form-group"><label>نام خانوادگی</label><input type="text" name="last_name" value="{{ old('last_name') }}"></div></div>
            <div class="form-group"><label>شماره موبایل</label><input type="tel" name="phone" value="{{ old('phone') }}" inputmode="numeric" maxlength="11" placeholder="09123456789" dir="ltr" required></div>
            <div class="form-row"><div class="form-group"><label>رمز عبور</label><input type="password" name="password" minlength="8" required><small>حداقل ۸ کاراکتر</small></div><div class="form-group"><label>تکرار رمز عبور</label><input type="password" name="password_confirmation" minlength="8" required></div></div>
            <div class="form-group"><label>نقش</label><select name="role" required>@foreach(['buyer'=>'خریدار','user'=>'کاربر','staff'=>'کارشناس','admin'=>'مدیر ارشد'] as $value=>$label)<option value="{{ $value }}" @selected(old('role','buyer')===$value)>{{ $label }}</option>@endforeach</select></div>
            <div class="form-actions"><a href="{{ route('admin.users.index') }}" class="admin-secondary-btn">انصراف</a><button type="submit" class="admin-primary-btn">ایجاد کاربر</button></div>
        </form>
    </div>
</div>
@endsection