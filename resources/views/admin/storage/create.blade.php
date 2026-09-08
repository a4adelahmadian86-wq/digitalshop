@extends('admin.layout')
@section('title','افزودن Storage')
@section('content')
<div class="admin-page narrow"><div class="admin-page-head"><div><div class="admin-eyebrow">Storage Management</div><h1>افزودن Storage Provider</h1><p>چند محل ذخیره از سرویس‌های مختلف را می‌توانید مستقل مدیریت کنید.</p></div></div>
@if($errors->any())<div class="admin-alert error">@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>@endif
<div class="admin-form-card"><form method="POST" action="{{ route('admin.storage.store') }}" class="admin-form">@csrf
<div class="form-group"><label>نام Provider</label><input name="name" value="{{ old('name') }}" placeholder="فضای ابری اصلی" required></div>
<div class="form-group"><label>نوع Provider</label><select name="type" id="storage-type"><option value="local">Local Storage</option><option value="api" @selected(old('type')==='api')>API Storage</option></select></div>
<div id="api-fields" hidden>
<div class="form-group"><label>Endpoint اصلی API</label><input type="url" name="endpoint" value="{{ old('endpoint') }}" dir="ltr" placeholder="https://example.com/api"></div>
<div class="form-row"><div class="form-group"><label>API Key</label><input type="password" name="api_key" dir="ltr" autocomplete="new-password"></div><div class="form-group"><label>سقف فضا (GB)</label><input type="number" name="limit_gb" value="{{ old('limit_gb',0) }}" min="0" step="0.01"></div></div>
<div class="form-row"><div class="form-group"><label>Upload path</label><input name="upload_path" value="{{ old('upload_path','upload') }}" dir="ltr"></div><div class="form-group"><label>Delete path</label><input name="delete_path" value="{{ old('delete_path','delete') }}" dir="ltr"></div></div>
<div class="form-row"><div class="form-group"><label>Exists path</label><input name="exists_path" value="{{ old('exists_path','exists') }}" dir="ltr"></div><div class="form-group"><label>Download path</label><input name="download_path" value="{{ old('download_path','download') }}" dir="ltr"></div></div>
<div class="form-row"><div class="form-group"><label>File field</label><input name="file_field" value="{{ old('file_field','file') }}" dir="ltr"></div><div class="form-group"><label>Path field</label><input name="path_field" value="{{ old('path_field','path') }}" dir="ltr"></div></div>
<div class="form-row"><div class="form-group"><label>Header name</label><input name="header_name" value="{{ old('header_name','Authorization') }}" dir="ltr"></div><div class="form-group"><label>Header prefix</label><input name="header_prefix" value="{{ old('header_prefix','Bearer ') }}" dir="ltr"></div></div>
<div class="discount-used-info">API Key رمزنگاری‌شده ذخیره می‌شود. برای هر سرویس می‌توان Endpoint، مسیرهای API، نام فیلدها و سقف فضا را جداگانه تنظیم کرد.</div>
</div>
<div class="form-actions"><a href="{{ route('admin.storage.index') }}" class="admin-secondary-btn">انصراف</a><button type="submit" class="admin-primary-btn">ذخیره Provider</button></div>
</form></div></div>
@endsection
@push('scripts')<script>document.addEventListener('DOMContentLoaded',()=>{const t=document.getElementById('storage-type'),f=document.getElementById('api-fields');const sync=()=>f.hidden=t.value!=='api';t.addEventListener('change',sync);sync();});</script>@endpush