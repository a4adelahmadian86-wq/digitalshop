@extends('account.layout')
@section('title','کیف پول')
@section('content')
<div class="account-head"><div><small>مالی</small><h1>کیف پول من</h1><p>موجودی و سابقه شارژ کیف پول در سایت.</p></div><a class="btn primary" href="{{ route('wallet.index') }}">شارژ کیف پول</a></div>
<div class="wallet-hero"><div><small>موجودی قابل استفاده</small><strong>{{ number_format((int)($wallet?->balance ?? 0)) }}</strong><span>تومان</span></div><div class="wallet-mark">◈</div></div>
<section class="account-card"><div class="card-title"><div><small>تراکنش‌های شارژ</small><h2>تاریخچه کیف پول</h2></div><a href="{{ route('wallet.index') }}">مدیریت کیف پول</a></div><div class="order-list">@forelse($topups as $topup)<div class="order-row"><span><b>{{ number_format((int)($topup->amount ?? 0)) }} تومان</b><small>{{ optional($topup->created_at)->format('Y/m/d H:i') }}</small></span><span><small>{{ match($topup->status ?? ''){'paid'=>'موفق','completed'=>'موفق','pending'=>'در انتظار','failed'=>'ناموفق',default=>$topup->status ?? '—'} }}</small></span></div>@empty<div class="empty">هنوز تراکنش شارژی ثبت نشده است.</div>@endforelse</div><div class="pagination">{{ $topups->links() }}</div></section>
@endsection
