@extends('admin.layout')
@section('title','افزودن دسته‌بندی')
@section('content')
<div class="admin-page narrow"><div class="admin-page-head"><div><div class="admin-eyebrow">مدیریت دسته‌بندی‌ها</div><h1>افزودن دسته‌بندی</h1><p>دسته اصلی یا زیرگروه جدید ایجاد کنید؛ نامک انگلیسی به‌صورت خودکار ساخته می‌شود.</p></div></div>
@if($errors->any())<div class="admin-alert error"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<section class="admin-form-card"><form method="POST" action="{{ route('admin.categories.store') }}" class="admin-form">@csrf
<div class="form-group"><label>نام دسته‌بندی</label><input type="text" name="name" value="{{ old('name') }}" placeholder="مثلاً آموزش برنامه‌نویسی" required></div>
<div class="form-group"><label>دسته والد</label><select name="parent_id"><option value="">دسته اصلی</option>@foreach($parents as $parent)<option value="{{ $parent->id }}" @selected(old('parent_id')==$parent->id)>{{ $parent->name }}</option>@endforeach</select><small>اگر انتخاب شود، این مورد به عنوان زیرگروه ثبت می‌شود.</small></div>
<div class="form-group"><label>توضیحات</label><textarea name="description" rows="5">{{ old('description') }}</textarea></div>
<div class="form-row"><div class="form-group"><label>تصویر</label><input type="text" name="image" value="{{ old('image') }}" placeholder="مسیر تصویر" dir="ltr"></div><div class="form-group"><label>ترتیب نمایش</label><input type="number" name="sort_order" value="{{ old('sort_order',0) }}" min="0"></div></div>
<div class="form-group"><label class="checkbox-label"><input type="checkbox" name="is_active" value="1" {{ old('is_active',true)?'checked':'' }}> دسته‌بندی فعال باشد</label></div>
<div class="form-actions"><a href="{{ route('admin.categories.index') }}" class="admin-secondary-btn">انصراف</a><button type="submit" class="admin-primary-btn">ایجاد دسته‌بندی</button></div>
</form></section></div>
@endsection