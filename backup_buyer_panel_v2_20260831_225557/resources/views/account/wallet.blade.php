@extends('account.layout')
@section('title','کیف پول')
@section('content')
<div class="account-head"><div><small>مالی</small><h1>کیف پول من</h1><p>موجودی و سابقه شارژ کیف پول.</p></div><a class="btn primary" href="{{ route('wallet.index') }}">شارژ کیف پول</a></div>
<section class="wallet-hero"><small>موجودی فعلی</small><strong>{{ number_format((int)($wallet?->balance ?? 0)) }}</strong><span>تومان</span></section>
<section class="account-card"><div class="card-title"><div><small>تاریخچه</small><h2>شارژهای کیف پول</h2></div></div><div class="order-list">@forelse($topups as $topup)<div class="order-row"><span><b>{{ number_format((int)($topup->amount ?? 0)) }} تومان</b><small>{{ optional($topup->created_at)->format('Y/m/d H:i') }}</small></span><span><small>{{ $topup->status ?? '—' }}</small></span></div>@empty<div class="empty">تراکنشی ثبت نشده است.</div>@endforelse</div><div class="pagination">{{ $topups->links() }}</div></section>
@endsection
