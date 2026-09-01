@extends('account.layout')
@section('title','جزئیات سفارش')
@section('content')
<div class="account-head"><div><small>سفارش {{ $order->order_number }}</small><h1>جزئیات سفارش</h1></div><a class="btn secondary" href="{{ route('account.orders') }}">بازگشت</a></div>
<section class="account-card"><div class="summary-grid"><div><small>شماره سفارش</small><strong>{{ $order->order_number }}</strong></div><div><small>وضعیت</small><strong>{{ $order->status }}</strong></div><div><small>مبلغ نهایی</small><strong>{{ number_format($order->total) }} تومان</strong></div><div><small>تاریخ</small><strong>{{ optional($order->created_at)->format('Y/m/d H:i') }}</strong></div></div></section>
<section class="account-card"><div class="card-title"><div><small>اقلام</small><h2>محصولات سفارش</h2></div></div><div class="order-list">@foreach($order->items as $item)<div class="order-row"><span><b>{{ $item->product?->title ?? 'محصول' }}</b><small>تعداد: {{ $item->quantity }}</small></span><span><b>{{ number_format($item->price) }} تومان</b></span></div>@endforeach</div><div class="detail-total"><span>مبلغ کل</span><strong>{{ number_format($order->total) }} تومان</strong></div></section>
@if($order->status === 'paid')<div class="account-actions"><a class="btn primary" href="{{ route('account.files') }}">مشاهده فایل‌های خریداری‌شده</a><a class="btn secondary" href="{{ route('account.invoice',$order) }}">مشاهده فاکتور</a></div>@endif
@endsection
