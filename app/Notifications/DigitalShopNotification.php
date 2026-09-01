<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class DigitalShopNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $category,
        public string $title,
        public string $message,
        public ?string $url = null,
        public ?string $actionLabel = null,
        public array $meta = [],
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'category' => $this->category,
            'title' => $this->title,
            'message' => $this->message,
            'url' => $this->url,
            'action_label' => $this->actionLabel,
            'meta' => $this->meta,
            'icon' => $this->icon(),
            'accent' => $this->accent(),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }

    public function icon(): string
    {
        return match ($this->category) {
            'success', 'order' => 'check-circle',
            'payment' => 'credit-card',
            'download' => 'download',
            'security' => 'shield-check',
            'promotion' => 'sparkles',
            'warning' => 'triangle-alert',
            default => 'bell',
        };
    }

    public function accent(): string
    {
        return match ($this->category) {
            'success', 'order' => 'success',
            'payment' => 'payment',
            'download' => 'download',
            'security' => 'security',
            'promotion' => 'promotion',
            'warning' => 'warning',
            default => 'system',
        };
    }

    public static function dailyRecommendationKey(int $productId): string
    {
        return 'daily-product-recommendation:' . now()->toDateString() . ':' . $productId;
    }
}
