@extends('admin.layout')
@section('title','ویرایش Storage')
@section('content')
@php($config=$storageProvider->config ?? [])
<div class="admin-page narrow"><div class="admin-page-head"><div><div class="admin-eyebrow">Storage Management</div><h1>ویرایش محل ذخیره‌سازی</h1><p>{{ $storageProvider->name }} — {{ $storageProvider->scopeLabel() }} / {{ $storageProvider->locationLabel() }}</p></div></div>
@if($errors->any())<div class="admin-alert error">@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>@endif
<div class="admin-form-card"><form method="POST" action="{{ route('admin.storage.update',$storageProvider) }}" class="admin-form">@csrf @method('PUT')
<div class="form-row"><div class="form-group"><label>نام Provider</label><input name="name" value="{{ old('name',$storageProvider->name) }}" required></div><div class="form-group"><label>اولویت</label><input type="number" name="priority" value="{{ old('priority',$storageProvider->priority) }}" min="1" required></div></div>
<div class="form-row"><div class="form-group"><label>بخش</label><select name="scope">@foreach(\App\Models\StorageProvider::SCOPES as $key=>$label)<option value="{{ $key }}" @selected(old('scope',$storageProvider->scope)===$key)>{{ $label }}</option>@endforeach</select></div><div class="form-group"><label>موقعیت</label><select name="location">@foreach(\App\Models\StorageProvider::LOCATIONS as $key=>$label)<option value="{{ $key }}" @selected(old('location',$storageProvider->location)===$key)>{{ $label }}</option>@endforeach</select></div></div>
<div class="form-group"><label>مستندات API</label><input type="url" name="documentation_url" value="{{ old('documentation_url',$storageProvider->documentation_url) }}" dir="ltr"></div>
@if($storageProvider->type==='api')
<div class="form-group"><label>API Key جدید</label><input type="password" name="api_key" dir="ltr" autocomplete="new-password" placeholder="برای حفظ کلید فعلی خالی بگذارید"></div>
<div class="form-row"><div class="form-group"><label>سقف فضا GB</label><input type="number" name="limit_gb" value="{{ old('limit_gb',isset($config['limit_bytes'])?round($config['limit_bytes']/1073741824,2):0) }}" min="0" step="0.01"></div><div class="form-group"><label>حداکثر فایل MB</label><input type="number" name="max_file_mb" value="{{ old('max_file_mb',isset($config['max_file_bytes'])?round($config['max_file_bytes']/1048576,2):0) }}" min="0" step="0.01"></div></div>
<div class="form-row"><div class="form-group"><label>حداکثر تعداد فایل</label><input type="number" name="max_files" value="{{ old('max_files',$config['max_files']??0) }}" min="0"></div><div class="form-group"><label>نگهداری موقت (روز)</label><input type="number" name="retention_days" value="{{ old('retention_days',$config['retention_days']??30) }}" min="1"></div></div>
@else<div class="discount-used-info">Provider داخلی از دیسک Local استفاده می‌کند و ظرفیت واقعی دیسک در داشبورد محاسبه می‌شود.</div>@endif
<div class="form-actions"><a href="{{ route('admin.storage.index') }}" class="admin-secondary-btn">بازگشت</a><button class="admin-primary-btn">ذخیره تغییرات</button></div>
</form></div></div>
@endsection