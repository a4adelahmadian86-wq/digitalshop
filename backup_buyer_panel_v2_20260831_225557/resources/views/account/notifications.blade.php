@extends('account.layout')
@section('title','اعلان‌ها')
@section('content')
<div class="account-head"><div><small>مرکز پیام</small><h1>اعلان‌ها</h1><p>آخرین اطلاع‌رسانی‌های حساب شما.</p></div></div>
<section class="account-card"><div class="notification-list">@forelse($notifications as $notification)<a class="notification-row {{ $notification->read_at ? '' : 'unread' }}" href="{{ route('account.notifications.read',$notification->id) }}"><strong>{{ $notification->data['title'] ?? 'اعلان جدید' }}</strong><p>{{ $notification->data['message'] ?? ($notification->data['body'] ?? '') }}</p><small>{{ optional($notification->created_at)->format('Y/m/d H:i') }}</small></a>@empty<div class="empty">اعلانی وجود ندارد.</div>@endforelse</div><div class="pagination">{{ $notifications->links() }}</div></section>
@endsection
