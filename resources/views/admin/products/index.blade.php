@extends('admin.layout')
@section('title', 'محصولات')
@section('content')
<div class="admin-page">
<div class="admin-page-head"><div><div class="admin-eyebrow">مدیریت فروشگاه</div><h1>محصولات</h1><p>محصولات، فایل‌ها، وضعیت بررسی هوشمند و فروش را یکجا مدیریت کنید.</p></div><a href="{{ route('admin.products.create') }}" class="admin-primary-btn">+ افزودن محصول</a></div>
@if(session('success'))<div class="admin-alert success">{{ session('success') }}</div>@endif
@if(session('error'))<div class="admin-alert error">{{ session('error') }}</div>@endif
<section class="admin-table-card"><div class="admin-table-wrap"><table class="admin-table"><thead><tr><th>محصول</th><th>دسته</th><th>قیمت</th><th>فایل</th><th>AI</th><th>انتشار</th><th>فروش</th><th>عملیات</th></tr></thead><tbody>
@forelse($products as $product)
<tr><td><strong>{{ $product->title }}</strong><div class="muted" style="margin-top:5px">{{ $product->slug }}</div></td><td>{{ $product->category?->name ?? '—' }}</td><td>{{ number_format($product->price) }} تومان</td><td>@if($product->storage_path)<span class="status-badge active">موجود</span>@else<span class="status-badge danger">بدون فایل</span>@endif</td><td><span class="status-badge {{ ($product->ai_status ?? '') === 'ready_for_review' ? 'active' : 'inactive' }}">{{ match($product->ai_status ?? 'not_checked'){'ready_for_review'=>'آماده بررسی','needs_review'=>'نیازمند بررسی','blocked'=>'مسدود','approved'=>'تأیید AI',default=>'بررسی نشده'} }}</span></td><td>@if($product->is_published)<span class="status-badge active">منتشر</span>@else<span class="status-badge inactive">پیش‌نویس</span>@endif</td><td>{{ number_format($product->sales_count) }}</td><td><div class="admin-actions"><a href="{{ route('admin.products.edit',$product) }}" class="action-btn edit">ویرایش</a><form method="POST" action="{{ route('admin.products.toggle',$product) }}">@csrf @method('PATCH')<button class="action-btn toggle">{{ $product->is_published ? 'پیش‌نویس' : 'انتشار' }}</button></form>@if(!$product->orderItems()->exists())<form method="POST" action="{{ route('admin.products.destroy',$product) }}" onsubmit="return confirm('آیا از حذف این محصول مطمئن هستید؟');">@csrf @method('DELETE')<button class="action-btn delete">حذف</button></form>@endif</div></td></tr>
@empty<tr><td colspan="8" style="text-align:center;padding:60px">هنوز محصولی ایجاد نشده است.<br><br><a href="{{ route('admin.products.create') }}" class="admin-primary-btn">افزودن اولین محصول</a></td></tr>@endforelse
</tbody></table></div></section><div style="margin-top:20px">{{ $products->links() }}</div>
</div>
@endsection
