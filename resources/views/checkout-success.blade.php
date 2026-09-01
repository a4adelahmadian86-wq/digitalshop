@extends('layouts.app')

@section('title','سفارش با موفقیت ثبت شد')

@section('content')

<div class="container success-page">

<div class="success-box">

<div class="success-icon">✓</div>

<h1>سفارش شما با موفقیت ثبت شد</h1>

<p>
شماره سفارش:
<strong>{{ $order->order_number }}</strong>
</p>

@if($order->status === 'paid')

<p class="success-text">
پرداخت با موفقیت تأیید شده است.
</p>

<h2>فایل‌های شما</h2>

@foreach($order->items as $item)

<a class="download-btn"
href="{{ route('download',$item) }}">
⬇ دانلود {{ $item->product->title }} </a>

@endforeach

<a
href="{{ route('account.invoice', $order) }}"
class="invoice-button"

>

```
مشاهده و چاپ فاکتور
```

</a>

@else

<p>
سفارش شما ثبت شده و پس از تأیید پرداخت،
لینک دانلود فعال خواهد شد.
</p>

@endif

</div>

</div>

@endsection
