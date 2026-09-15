@extends('admin.layout')

@section('title', 'نقش‌ها و دسترسی‌ها')

@section('content')
<div class="admin-page">
    <div class="admin-page-head">
        <div>
            <div class="admin-eyebrow">کنترل دسترسی</div>
            <h1>نقش‌ها و دسترسی‌ها</h1>
            <p>مجوزهای هر نقش را به‌صورت صریح مدیریت کنید. مدیر ارشد عمداً از محدودسازی این صفحه مستثناست.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="admin-alert admin-alert-success" role="status">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="admin-alert admin-alert-danger" role="alert">{{ $errors->first() }}</div>
    @endif

    <div class="admin-box">
        <div class="admin-box-head">
            <div>
                <span>RBAC</span>
                <h2>مجوزهای نقش</h2>
            </div>
        </div>

        @foreach($roles as $role)
            <form method="POST" action="{{ route('admin.access.update', $role) }}" class="admin-access-role">
                @csrf
                @method('PUT')
                <div class="admin-access-role-head">
                    <div>
                        <strong>{{ $role }}</strong>
                        @if($role === 'admin')<small>دسترسی کامل سیستم</small>@endif
                    </div>
                    @if($role !== 'admin')
                        <button type="submit" class="admin-btn admin-btn-primary">ذخیره مجوزها</button>
                    @else
                        <span class="admin-badge">محافظت‌شده</span>
                    @endif
                </div>

                <div class="admin-access-grid">
                    @foreach($permissions as $group => $items)
                        <fieldset>
                            <legend>{{ $group }}</legend>
                            @foreach($items as $permission)
                                <label class="admin-permission-row">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                        @checked($role === 'admin' || in_array($permission->name, $assignments->get($role, []), true))
                                        @disabled($role === 'admin')>
                                    <span><b>{{ $permission->name }}</b><small>{{ $permission->label }}</small></span>
                                </label>
                            @endforeach
                        </fieldset>
                    @endforeach
                </div>
            </form>
        @endforeach
    </div>

    <div class="admin-box" style="margin-top:18px">
        <div class="admin-box-head">
            <div><span>امنیت</span><h2>آخرین رویدادهای دسترسی</h2></div>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>زمان</th><th>عملیات</th><th>عامل</th><th>نتیجه</th></tr></thead>
                <tbody>
                @forelse($recentAudits as $audit)
                    <tr>
                        <td>{{ optional($audit->created_at)->format('Y/m/d H:i') }}</td>
                        <td>{{ $audit->action }}</td>
                        <td>{{ trim(($audit->actor?->first_name ?? '') . ' ' . ($audit->actor?->last_name ?? '')) ?: 'سیستم' }}</td>
                        <td>{{ $audit->outcome }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4">هنوز رویداد ثبت نشده است.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.admin-alert{padding:12px 14px;border-radius:10px;margin-bottom:16px;font-size:12px}.admin-alert-success{background:#ecfdf3;color:#027a48}.admin-alert-danger{background:#fef3f2;color:#b42318}.admin-access-role{padding:20px 0;border-top:1px solid var(--admin-border)}.admin-access-role:first-of-type{border-top:0}.admin-access-role-head{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:18px}.admin-access-role-head strong{display:block;font-size:15px}.admin-access-role-head small{display:block;margin-top:4px;color:var(--admin-text-muted);font-size:11px}.admin-access-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}.admin-access-grid fieldset{border:1px solid var(--admin-border);border-radius:12px;padding:12px;min-width:0}.admin-access-grid legend{padding:0 6px;color:var(--admin-text-secondary);font-size:11px;font-weight:800}.admin-permission-row{display:flex;gap:9px;align-items:flex-start;padding:8px 4px;border-radius:8px}.admin-permission-row:hover{background:var(--admin-surface-soft)}.admin-permission-row input{margin-top:3px}.admin-permission-row b{display:block;font-size:11px}.admin-permission-row small{display:block;color:var(--admin-text-muted);font-size:10px;margin-top:2px}.admin-btn{border:0;border-radius:9px;padding:9px 13px;cursor:pointer;font-weight:700}.admin-btn-primary{color:#fff;background:var(--admin-primary)}.admin-badge{padding:5px 9px;border-radius:20px;background:var(--admin-primary-soft);color:var(--admin-primary);font-size:10px;font-weight:800}@media(max-width:900px){.admin-access-grid{grid-template-columns:1fr}}@media(max-width:600px){.admin-access-role-head{align-items:flex-start;flex-direction:column}.admin-btn{width:100%}}
</style>
@endsection