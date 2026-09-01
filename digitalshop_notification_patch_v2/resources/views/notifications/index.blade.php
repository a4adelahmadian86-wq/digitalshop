@extends('layouts.app')

@section('title', 'اعلان‌ها')

@section('content')
<div class="container notifications-page">
    <div class="notifications-head">
        <div>
            <span class="notifications-eyebrow">مرکز اعلان‌ها</span>
            <h1>اعلان‌های شما</h1>
            <p>پیام‌های مهم حساب، سفارش، پرداخت و پیشنهادها را اینجا می‌بینید.</p>
        </div>

        @if(auth()->user()->unreadNotifications()->exists())
            <form method="POST" action="{{ route('notifications.read-all') }}">
                @csrf
                <button class="notifications-read-all" type="submit">علامت‌گذاری همه به‌عنوان خوانده‌شده</button>
            </form>
        @endif
    </div>

    <div class="notifications-list">
        @forelse($notifications as $notification)
            @php($data = $notification->data)
            @php($category = data_get($data, 'category', 'system'))
            <article class="notification-card notification-{{ $category }} {{ $notification->read_at ? 'is-read' : 'is-unread' }}">
                <div class="notification-icon notification-icon-{{ $category }}">
                    @include('notifications.icons', ['name' => data_get($data, 'icon', 'bell')])
                </div>
                <div class="notification-body">
                    <div class="notification-topline">
                        <h2>{{ data_get($data, 'title', 'اعلان') }}</h2>
                        <time datetime="{{ $notification->created_at->toIso8601String() }}">{{ $notification->created_at->diffForHumans() }}</time>
                    </div>
                    <p>{{ data_get($data, 'message') }}</p>
                    <div class="notification-actions">
                        @if(data_get($data, 'url'))
                            <a href="{{ route('notifications.read', $notification->id) }}">{{ data_get($data, 'action_label', 'مشاهده') }}</a>
                        @endif
                        @if(!$notification->read_at)
                            <a class="notification-muted-action" href="{{ route('notifications.read', $notification->id) }}">خواندم</a>
                        @endif
                    </div>
                </div>
            </article>
        @empty
            <div class="notifications-empty">
                <div class="notification-empty-icon">@include('notifications.icons', ['name' => 'bell'])</div>
                <h2>اعلانی ندارید</h2>
                <p>وقتی اتفاق مهمی برای حساب یا سفارش شما رخ دهد، اینجا نمایش داده می‌شود.</p>
            </div>
        @endforelse
    </div>

    <div class="notifications-pagination">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
