@extends('admin.layout')
@section('title','ویرایش دسته‌بندی')
@section('content')
<div class="admin-page narrow"><div class="admin-page-head"><div><div class="admin-eyebrow">مدیریت دسته‌بندی‌ها</div><h1>ویرایش دسته‌بندی</h1><p>{{ $category->name }} · شناسه #{{ $category->id }} · نامک توسط سیستم مدیریت می‌شود.</p></div></div>
@if($errors->any())<div class="admin-alert error"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<section class="admin-form-card"><form method="POST" action="{{ route('admin.categories.update',$category) }}" class="admin-form">@csrf @method('PUT')
<div class="form-group"><label>نام دسته‌بندی</label><input type="text" name="name" value="{{ old('name',$category->name) }}" required></div>
<div class="form-group"><label>دسته والد</label><select name="parent_id"><option value="">دسته اصلی</option>@foreach($parents as $parent)<option value="{{ $parent->id }}" @selected(old('parent_id',$category->parent_id)==$parent->id)>{{ $parent->name }}</option>@endforeach</select></div>
<div class="form-group"><label>توضیحات</label><textarea name="description" rows="5">{{ old('description',$category->description) }}</textarea></div>
<div class="form-row"><div class="form-group"><label>تصویر</label><input type="text" name="image" value="{{ old('image',$category->image) }}" dir="ltr"></div><div class="form-group"><label>ترتیب نمایش</label><input type="number" name="sort_order" value="{{ old('sort_order',$category->sort_order) }}" min="0"></div></div>
<div class="form-group"><label class="checkbox-label"><input type="checkbox" name="is_active" value="1" {{ old('is_active',$category->is_active)?'checked':'' }}> دسته‌بندی فعال باشد</label></div>
@if($category->products()->exists()||$category->children()->exists())<div class="discount-used-info">این دسته‌بندی دارای وابستگی است و تا زمانی که محصولات یا زیرگروه‌ها جابه‌جا نشوند قابل حذف نیست.</div>@endif
<div class="form-actions"><a href="{{ route('admin.categories.index') }}" class="admin-secondary-btn">انصراف</a><button type="submit" class="admin-primary-btn">ذخیره تغییرات</button></div>
</form></section></div>
@endsection