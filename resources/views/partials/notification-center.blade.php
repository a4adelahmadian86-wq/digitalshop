@auth
@php($notificationCount = auth()->user()->unreadNotifications()->count())
<div class="notification-center" data-notification-center>
    <a class="notification-bell" href="{{ route('notifications.index') }}" aria-label="اعلان‌ها" title="اعلان‌ها">
        @include('notifications.icons', ['name' => 'bell'])
        @if($notificationCount > 0)
            <span class="notification-badge">{{ $notificationCount > 99 ? '99+' : $notificationCount }}</span>
        @endif
    </a>
</div>
@endauth
