@extends('account.layout')
@section('title','سفارش‌های من')
@section('content')
<div class="account-head"><div><small>حساب کاربری</small><h1>سفارش‌های من</h1><p>تاریخچه کامل خریدهای شما.</p></div></div>
<section class="account-card"><div class="table-wrap"><table class="account-table"><thead><tr><th>شماره سفارش</th><th>تاریخ</th><th>مبلغ</th><th>وضعیت</th><th></th></tr></thead><tbody>@forelse($orders as $order)<tr><td><b>{{ $order->order_number }}</b></td><td>{{ optional($order->created_at)->format('Y/m/d') }}</td><td>{{ number_format($order->total) }} تومان</td><td><span class="badge">{{ $order->status }}</span></td><td><a class="table-link" href="{{ route('account.orders.show',$order) }}">جزئیات</a></td></tr>@empty<tr><td colspan="5" class="empty">هنوز سفارشی ثبت نشده است.</td></tr>@endforelse</tbody></table></div><div class="pagination">{{ $orders->links() }}</div></section>
@endsection
