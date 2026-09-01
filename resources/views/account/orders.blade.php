@extends('account.layout')
@section('title','سفارش‌های من')
@section('content')
<div class="account-head"><div><small>حساب کاربری</small><h1>سفارش‌های من</h1><p>تاریخچه کامل خریدها و وضعیت پرداخت شما.</p></div><a class="btn secondary" href="{{ route('products.index') }}">خرید جدید</a></div>
<div class="filter-tabs">
    <a class="{{ !$status ? 'active' : '' }}" href="{{ route('account.orders') }}">همه</a>
    <a class="{{ $status === 'paid' ? 'active' : '' }}" href="{{ route('account.orders',['status'=>'paid']) }}">پرداخت‌شده</a>
    <a class="{{ $status === 'pending' ? 'active' : '' }}" href="{{ route('account.orders',['status'=>'pending']) }}">در انتظار پرداخت</a>
    <a class="{{ $status === 'failed' ? 'active' : '' }}" href="{{ route('account.orders',['status'=>'failed']) }}">ناموفق</a>
</div>
<section class="account-card"><div class="table-wrap"><table class="account-table"><thead><tr><th>سفارش</th><th>تاریخ</th><th>مبلغ</th><th>وضعیت</th><th>عملیات</th></tr></thead><tbody>
@forelse($orders as $order)
<tr><td><b>{{ $order->order_number }}</b><small>{{ $order->items->count() }} محصول</small></td><td>{{ optional($order->created_at)->format('Y/m/d H:i') }}</td><td><b>{{ number_format($order->total) }}</b> تومان</td><td><span class="badge {{ $order->status }}">{{ match($order->status){'paid'=>'پرداخت‌شده','completed'=>'تکمیل‌شده','pending'=>'در انتظار پرداخت','failed'=>'ناموفق','cancelled'=>'لغوشده',default=>$order->status} }}</span></td><td><a class="table-link" href="{{ route('account.orders.show',$order) }}">جزئیات</a></td></tr>
@empty<tr><td colspan="5" class="empty">هنوز سفارشی با این فیلتر پیدا نشد.</td></tr>@endforelse
</tbody></table></div><div class="pagination">{{ $orders->links() }}</div></section>
@endsection
