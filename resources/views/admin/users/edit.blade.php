@extends('admin.layout')

@section('title', 'ویرایش کاربر')

@section('content')
<div class="admin-page narrow">
    <div class="admin-page-head">
        <div><div class="admin-eyebrow">کاربران</div><h1>ویرایش کاربر</h1><p>{{ $user->phone }}</p></div>
    </div>

    @if($errors->any())
        <div class="admin-alert error"><ul style="margin:0;padding-right:20px">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <div class="admin-form-card">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="admin-form">
            @csrf @method('PUT')
            <div class="form-row">
                <div class="form-group"><label>نام</label><input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" required></div>
                <div class="form-group"><label>نام خانوادگی</label><input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}"></div>
            </div>
            <div class="form-group"><label>شماره موبایل</label><input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" inputmode="numeric" maxlength="11" dir="ltr" required></div>
            <div class="form-group">
                <label>نقش</label>
                <select name="role" required @disabled(!auth()->user()->isAdmin())>
                    @foreach(['buyer' => 'خریدار', 'user' => 'کاربر', 'staff' => 'کارشناس', 'admin' => 'مدیر ارشد'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('role', $user->role) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @if(!auth()->user()->isAdmin())<small>تغییر نقش فقط توسط مدیر ارشد مجاز است.</small>@endif
            </div>
            @if(!auth()->user()->isAdmin())<input type="hidden" name="role" value="{{ $user->role }}">@endif
            <div class="form-row">
                <div class="form-group"><label>رمز عبور جدید</label><input type="password" name="password" minlength="8"><small>در صورت نیاز تغییر دهید.</small></div>
                <div class="form-group"><label>تکرار رمز عبور</label><input type="password" name="password_confirmation" minlength="8"></div>
            </div>
            <div class="discount-used-info">وضعیت: <strong>{{ $user->is_active ? 'فعال' : 'غیرفعال' }}</strong><br>تأیید موبایل: <strong>{{ $user->phone_verified_at ? 'تأیید شده' : 'تأیید نشده' }}</strong></div>
            <div class="form-actions"><a href="{{ route('admin.users.index') }}" class="admin-secondary-btn">بازگشت</a><button type="submit" class="admin-primary-btn">ذخیره تغییرات</button></div>
        </form>
    </div>
</div>
@endsection