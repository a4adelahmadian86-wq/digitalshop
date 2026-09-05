@extends('admin.layout')
@section('title','مدیریت سفارش‌ها')
@section('content')
<div class="panel-content">
    <div class="account-head"><div><small>مدیریت سامانه</small><h1>سفارش‌ها</h1><p>مشاهده، جستجو و مدیریت وضعیت سفارش‌های فروشگاه.</p></div></div>
    <div class="panel-widgets">
        <a class="widget-card" href="{{ route('admin.orders.index') }}"><span class="widget-icon">کل</span><strong class="widget-value">{{ number_format($stats['total']) }}</strong><small>همه سفارش‌ها</small></a>
        <a class="widget-card" href="{{ route('admin.orders.index',['status'=>'pending']) }}"><span class="widget-icon">در</span><strong class="widget-value">{{ number_format($stats['pending']) }}</strong><small>در انتظار پرداخت</small></a>
        <a class="widget-card" href="{{ route('admin.orders.index',['status'=>'paid']) }}"><span class="widget-icon">مو</span><strong class="widget-value">{{ number_format($stats['paid']) }}</strong><small>پرداخت موفق</small></a>
        <a class="widget-card" href="{{ route('admin.orders.index',['status'=>'completed']) }}"><span class="widget-icon">تک</span><strong class="widget-value">{{ number_format($stats['completed']) }}</strong><small>تکمیل‌شده</small></a>
    </div>
    <section class="panel-section">
        <form method="GET" class="admin-filter" style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:16px">
            <input name="q" value="{{ $q }}" placeholder="شماره سفارش، نام یا موبایل" class="form-control" style="min-width:260px">
            <select name="status" class="form-control"><option value="">همه وضعیت‌ها</option>@foreach(['pending'=>'در انتظار پرداخت','paid'=>'موفق','completed'=>'تکمیل‌شده','failed'=>'ناموفق','cancelled'=>'لغوشده','refunded'=>'استرداد وجه'] as $value=>$label)<option value="{{ $value }}" @selected($status===$value)>{{ $label }}</option>@endforeach</select>
            <button class="btn primary" type="submit">فیلتر</button>
            @if($q!==''||$status!=='')<a class="btn secondary" href="{{ route('admin.orders.index') }}">حذف فیلتر</a>@endif
        </form>
        <div class="table-wrap"><table class="panel-table"><thead><tr><th>سفارش</th><th>کاربر</th><th>اقلام</th><th>مبلغ</th><th>وضعیت</th><th>تاریخ</th><th>عملیات</th></tr></thead><tbody>
        @forelse($orders as $order)
            <tr><td><b>#{{ $order->order_number }}</b></td><td>{{ trim(($order->user?->first_name??'').' '.($order->user?->last_name??'')) ?: 'کاربر حذف‌شده' }}<small>{{ $order->user?->phone }}</small></td><td>{{ number_format($order->items->count()) }}</td><td>{{ number_format($order->total) }} تومان</td><td><span class="status-badge">{{ ['pending'=>'در انتظار پرداخت','paid'=>'موفق','completed'=>'تکمیل‌شده','failed'=>'ناموفق','cancelled'=>'لغوشده','refunded'=>'استرداد وجه'][$order->status]??$order->status }}</span></td><td>{{ optional($order->created_at)->format('Y/m/d H:i') }}</td><td><a class="btn secondary" href="{{ route('admin.orders.show',$order) }}">جزئیات</a></td></tr>
        @empty<tr><td colspan="7" style="text-align:center;padding:30px">سفارشی با این فیلتر پیدا نشد.</td></tr>@endforelse
        </tbody></table></div>
        <div style="margin-top:16px">{{ $orders->links() }}</div>
    </section>
</div>
@endsection
