@extends('account.layout')
@section('title','جزئیات سفارش')
@section('content')
<div class="account-head"><div><small>سفارش {{ $order->order_number }}</small><h1>جزئیات سفارش</h1><p>اطلاعات پرداخت و محصولات این سفارش.</p></div><a class="btn secondary" href="{{ route('account.orders') }}">بازگشت</a></div>
<section class="account-card">
<div class="summary-grid"><div><small>شماره سفارش</small><strong>{{ $order->order_number }}</strong></div><div><small>وضعیت</small><strong>{{ match($order->status){'paid'=>'پرداخت‌شده','completed'=>'تکمیل‌شده','pending'=>'در انتظار پرداخت','failed'=>'ناموفق','cancelled'=>'لغوشده',default=>$order->status} }}</strong></div><div><small>مبلغ نهایی</small><strong>{{ number_format($order->total) }} تومان</strong></div><div><small>تاریخ ثبت</small><strong>{{ optional($order->created_at)->format('Y/m/d H:i') }}</strong></div></div>
@if($order->payment)<div class="payment-strip"><span>وضعیت تراکنش: <b>{{ $order->payment->status ?? '—' }}</b></span>@if($order->payment->transaction_id)<span>شماره تراکنش: <b dir="ltr">{{ $order->payment->transaction_id }}</b></span>@endif</div>@endif
</section>
<section class="account-card"><div class="card-title"><div><small>اقلام سفارش</small><h2>محصولات خریداری‌شده</h2></div></div><div class="order-list">@foreach($order->items as $item)<div class="order-row"><span><b>{{ $item->product?->title ?? 'محصول حذف‌شده' }}</b><small>تعداد: {{ $item->quantity }}</small></span><span><b>{{ number_format($item->price) }} تومان</b></span></div>@endforeach</div><div class="totals"><span>جمع جزء <b>{{ number_format($order->subtotal) }} تومان</b></span><span>تخفیف <b>{{ number_format($order->discount) }} تومان</b></span><strong>مبلغ نهایی <b>{{ number_format($order->total) }} تومان</b></strong></div></section>
<div class="account-actions">
@if(!in_array($order->status,['paid','completed','cancelled']))<a class="btn primary" href="{{ route('payment.pay',$order) }}">ادامه پرداخت</a>@endif
@if(in_array($order->status,['paid','completed']))<a class="btn primary" href="{{ route('account.files') }}">دریافت فایل‌های خرید</a>@endif
<a class="btn secondary" href="{{ route('account.invoice',$order) }}">مشاهده فاکتور</a>
</div>
@endsection
