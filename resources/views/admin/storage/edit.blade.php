@extends('admin.layout')
@section('title','ویرایش Storage')
@section('content')
@php($config=$storageProvider->config ?? [])
<div class="admin-page narrow"><div class="admin-page-head"><div><div class="admin-eyebrow">Storage Management</div><h1>ویرایش Storage Provider</h1><p>{{ $storageProvider->name }} — {{ strtoupper($storageProvider->type) }}</p></div></div>
@if($errors->any())<div class="admin-alert error">@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>@endif
<div class="admin-form-card"><form method="POST" action="{{ route('admin.storage.update',$storageProvider) }}" class="admin-form">@csrf @method('PUT')
<div class="form-group"><label>نام Provider</label><input name="name" value="{{ old('name',$storageProvider->name) }}" required></div>
@if($storageProvider->type==='api')
<div class="form-group"><label>Endpoint اصلی API</label><input type="url" name="endpoint" value="{{ old('endpoint',$config['endpoint'] ?? '') }}" dir="ltr"></div>
<div class="form-row"><div class="form-group"><label>API Key جدید</label><input type="password" name="api_key" dir="ltr" autocomplete="new-password" placeholder="برای حفظ کلید فعلی خالی بگذارید"></div><div class="form-group"><label>سقف فضا (GB)</label><input type="number" name="limit_gb" value="{{ old('limit_gb',isset($config['limit_bytes']) ? round($config['limit_bytes']/1073741824,2) : 0) }}" min="0" step="0.01"></div></div>
<div class="form-row"><div class="form-group"><label>Upload path</label><input name="upload_path" value="{{ old('upload_path',$config['upload_path'] ?? 'upload') }}" dir="ltr"></div><div class="form-group"><label>Delete path</label><input name="delete_path" value="{{ old('delete_path',$config['delete_path'] ?? 'delete') }}" dir="ltr"></div></div>
<div class="form-row"><div class="form-group"><label>Exists path</label><input name="exists_path" value="{{ old('exists_path',$config['exists_path'] ?? 'exists') }}" dir="ltr"></div><div class="form-group"><label>Download path</label><input name="download_path" value="{{ old('download_path',$config['download_path'] ?? 'download') }}" dir="ltr"></div></div>
<div class="form-row"><div class="form-group"><label>File field</label><input name="file_field" value="{{ old('file_field',$config['file_field'] ?? 'file') }}" dir="ltr"></div><div class="form-group"><label>Path field</label><input name="path_field" value="{{ old('path_field',$config['path_field'] ?? 'path') }}" dir="ltr"></div></div>
<div class="form-row"><div class="form-group"><label>Header name</label><input name="header_name" value="{{ old('header_name',$config['header_name'] ?? 'Authorization') }}" dir="ltr"></div><div class="form-group"><label>Header prefix</label><input name="header_prefix" value="{{ old('header_prefix',$config['header_prefix'] ?? 'Bearer ') }}" dir="ltr"></div></div>
@else<div class="discount-used-info">این Provider از دیسک Local استفاده می‌کند و ظرفیت واقعی دیسک در صفحه Storage نمایش داده می‌شود.</div>@endif
<div class="form-actions"><a href="{{ route('admin.storage.index') }}" class="admin-secondary-btn">بازگشت</a><button class="admin-primary-btn">ذخیره تغییرات</button></div>
</form></div></div>
@endsection