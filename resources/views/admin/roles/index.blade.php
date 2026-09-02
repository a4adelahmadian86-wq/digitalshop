@extends('admin.layout')
@section('title','نقش‌ها و دسترسی‌ها')
@section('content')
<div class="admin-page role-page">
<h1>نقش‌ها و دسترسی‌ها</h1>
<p class="admin-muted">یک حساب می‌تواند هم‌زمان خریدار و فروشنده باشد. برای هر نقش، منوها و عملیات مجاز را دقیق انتخاب کنید.</p>
<div class="role-grid">
@foreach($roles as $role)
<form method="POST" action="{{ route('admin.roles.update',$role) }}" class="role-card">
@csrf @method('PUT')
<div class="role-head"><div><h2>{{ $role->label }}</h2><small>{{ $role->name }}</small></div>@if($role->is_system)<span class="role-system">سیستمی</span>@endif</div>
<label>نام نمایشی<input name="label" value="{{ $role->label }}" required></label>
<label>توضیح<input name="description" value="{{ $role->description }}"></label>
<div class="permission-groups">
@foreach($permissions->groupBy('group_name') as $group=>$items)
<fieldset><legend>{{ $items->first()->group_name === $group ? match($group){'dashboard'=>'داشبورد','products'=>'محصولات','orders'=>'سفارش‌ها','users'=>'کاربران','categories'=>'دسته‌بندی‌ها','discounts'=>'کدهای تخفیف','notifications'=>'اعلانات','ai'=>'هوش مصنوعی','storage'=>'ذخیره‌سازی','customers'=>'مشتریان',default=>$group} : $group }}</legend>
@foreach($items as $permission)<label class="check"><input type="checkbox" name="permissions[]" value="{{ $permission->id }}" @checked($role->permissions->contains($permission->id))><span>{{ $permission->label }}</span></label>@endforeach
</fieldset>
@endforeach
</div><button class="admin-btn" type="submit">ذخیره دسترسی‌ها</button>
</form>
@endforeach
</div>
<div class="role-card create-role"><h2>تعریف نقش جدید</h2><form method="POST" action="{{ route('admin.roles.store') }}">@csrf<div class="role-form"><input name="name" placeholder="شناسه نقش، مثل content_manager" required><input name="label" placeholder="نام نقش" required><input name="description" placeholder="توضیح کوتاه"></div><div class="permission-groups compact">@foreach($permissions->groupBy('group_name') as $group=>$items)<fieldset><legend>{{ $items->first()->group_name }}</legend>@foreach($items as $permission)<label class="check"><input type="checkbox" name="permissions[]" value="{{ $permission->id }}"><span>{{ $permission->label }}</span></label>@endforeach</fieldset>@endforeach</div><button class="admin-btn" type="submit">ایجاد نقش</button></form></div>
</div>
@endsection
@push('styles')<style>
.role-page{max-width:1250px;margin:auto;padding:28px}.role-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(360px,1fr));gap:18px}.role-card{background:#fff;border:1px solid #e7e9ef;border-radius:22px;padding:22px;box-shadow:0 10px 30px #10182808}.role-head{display:flex;justify-content:space-between;align-items:start}.role-card h2{margin:0 0 3px}.role-card small,.admin-muted{color:#667085}.role-system{padding:5px 9px;border-radius:99px;background:#eef2ff;color:#4f46e5;font-size:11px}.role-card label:not(.check){display:block;margin-top:12px;font-size:13px}.role-card input[type=text],.role-card input:not([type]){width:100%;margin-top:5px;padding:10px;border:1px solid #dfe3ea;border-radius:10px}.permission-groups{display:grid;gap:10px;margin:18px 0}.permission-groups fieldset{border:1px solid #edf0f4;border-radius:14px;padding:10px}.permission-groups legend{font-weight:800;font-size:13px;padding:0 6px}.check{display:flex!important;align-items:center;gap:8px;margin:7px 0!important;font-size:12px}.check input{accent-color:#6541d8}.admin-btn{border:0;background:#6541d8;color:#fff;border-radius:11px;padding:10px 16px;cursor:pointer;font-weight:800}.role-form{display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px}.role-form input{padding:11px;border:1px solid #dfe3ea;border-radius:10px}.create-role{grid-column:1/-1}.compact{grid-template-columns:repeat(auto-fit,minmax(250px,1fr))}@media(max-width:700px){.role-page{padding:15px}.role-grid{grid-template-columns:1fr}.role-form{grid-template-columns:1fr}}
</style>@endpush
