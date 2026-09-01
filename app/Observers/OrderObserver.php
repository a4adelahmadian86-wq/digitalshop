<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\User;
use App\Services\NotificationService;

class OrderObserver
{
    public function created(Order $order): void
    {
        $user = User::find($order->user_id);

        if (!$user) {
            return;
        }

        app(NotificationService::class)->sendToUser(
            $user,
            'order',
            'سفارش شما ثبت شد',
            'سفارش ' . $order->order_number . ' با موفقیت ثبت شد.',
            route('checkout.success', $order),
            'مشاهده سفارش',
            ['order_id' => $order->id, 'order_number' => $order->order_number]
        );
    }

    public function updated(Order $order): void
    {
        if (!$order->wasChanged('status')) {
            return;
        }

        $user = User::find($order->user_id);

        if (!$user) {
            return;
        }

        $status = $order->status;

        if ($status === 'paid') {
            app(NotificationService::class)->sendToUser(
                $user,
                'payment',
                'پرداخت با موفقیت تأیید شد',
                'پرداخت سفارش ' . $order->order_number . ' تأیید شد و فایل‌های شما آماده دریافت هستند.',
                route('checkout.success', $order),
                'مشاهده فایل‌ها',
                ['order_id' => $order->id, 'order_number' => $order->order_number]
            );
            return;
        }

        if ($status === 'failed') {
            app(NotificationService::class)->sendToUser(
                $user,
                'warning',
                'پرداخت ناموفق بود',
                'پرداخت سفارش ' . $order->order_number . ' تأیید نشد. می‌توانید دوباره تلاش کنید.',
                route('checkout.success', $order),
                'مشاهده سفارش',
                ['order_id' => $order->id, 'order_number' => $order->order_number]
            );
        }
    }
}
