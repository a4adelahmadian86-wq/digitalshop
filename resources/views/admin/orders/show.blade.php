@extends('admin.layout')
@section('title','جزئیات سفارش '.$order->order_number)
@section('content')
<div class="panel-content">
    <div class="account-head"><div><small>سفارش #{{ $order->order_number }}</small><h1>جزئیات سفارش</h1><p>اطلاعات کاربر، اقلام، مبلغ و وضعیت پرداخت.</p></div><a class="btn secondary" href="{{ route('admin.orders.index') }}">بازگشت به سفارش‌ها</a></div>
    <section class="panel-section">
        <div class="panel-widgets"><div class="widget-card"><small>مبلغ نهایی</small><strong class="widget-value">{{ number_format($order->total) }}</strong><span>تومان</span></div><div class="widget-card"><small>کاربر</small><strong>{{ trim(($order->user?->first_name??'').' '.($order->user?->last_name??'')) ?: 'کاربر حذف‌شده' }}</strong><span>{{ $order->user?->phone }}</span></div><div class="widget-card"><small>روش پرداخت</small><strong>{{ $order->payment_method ?: 'ثبت نشده' }}</strong><span>{{ $order->paid_at?->format('Y/m/d H:i') ?: 'بدون پرداخت' }}</span></div></div>
        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin:18px 0"><strong>تغییر وضعیت:</strong><form method="POST" action="{{ route('admin.orders.status',$order) }}" style="display:flex;gap:8px;align-items:center">@csrf @method('PATCH')<select name="status" class="form-control">@foreach(['pending'=>'در انتظار پرداخت','paid'=>'موفق','completed'=>'تکمیل‌شده','failed'=>'ناموفق','cancelled'=>'لغوشده','refunded'=>'استرداد وجه'] as $value=>$label)<option value="{{ $value }}" @selected($order->status===$value)>{{ $label }}</option>@endforeach</select><button class="btn primary" type="submit">ذخیره وضعیت</button></form></div>
        <div class="table-wrap"><table class="panel-table"><thead><tr><th>محصول</th><th>تعداد</th><th>قیمت واحد</th><th>جمع</th></tr></thead><tbody>@forelse($order->items as $item)<tr><td><b>{{ $item->product?->title ?: 'محصول حذف‌شده' }}</b></td><td>{{ number_format($item->quantity ?? 1) }}</td><td>{{ number_format($item->price) }} تومان</td><td>{{ number_format(($item->price??0)*($item->quantity??1)) }} تومان</td></tr>@empty<tr><td colspan="4">اقلامی برای این سفارش ثبت نشده است.</td></tr>@endforelse</tbody></table></div>
    </section>
</div>
@endsection
