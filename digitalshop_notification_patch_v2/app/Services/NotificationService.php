<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Notifications\DigitalShopNotification;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    public function sendToRole(
        string|array $roles,
        string $category,
        string $title,
        string $message,
        ?string $url = null,
        ?string $actionLabel = null,
        array $meta = [],
    ): int {
        $roles = (array) $roles;

        $users = User::query()
            ->where('is_active', true)
            ->whereIn('role', $roles)
            ->get();

        if ($users->isEmpty()) {
            return 0;
        }

        Notification::send(
            $users,
            new DigitalShopNotification(
                $category,
                $title,
                $message,
                $url,
                $actionLabel,
                $meta,
            )
        );

        return $users->count();
    }

    public function sendToUser(
        User $user,
        string $category,
        string $title,
        string $message,
        ?string $url = null,
        ?string $actionLabel = null,
        array $meta = [],
    ): void {
        if (!$user->is_active) {
            return;
        }

        $user->notify(new DigitalShopNotification(
            $category,
            $title,
            $message,
            $url,
            $actionLabel,
            $meta,
        ));
    }

    /**
     * Send at most 3 product recommendations per user per calendar day.
     * Existing recommendation notifications for today are reused as the
     * duplicate guard, so repeated command runs do not send extra products.
     */
    public function sendDailyRecommendations(): int
    {
        $sent = 0;

        User::query()
            ->where('is_active', true)
            ->where('role', 'buyer')
            ->chunkById(200, function ($users) use (&$sent) {
                foreach ($users as $user) {
                    $existing = $user->notifications()
                        ->where('type', DigitalShopNotification::class)
                        ->whereDate('created_at', now()->toDateString())
                        ->get()
                        ->filter(fn ($notification) =>
                            data_get($notification->data, 'category') === 'promotion'
                            && data_get($notification->data, 'meta.kind') === 'daily_product_recommendation'
                        );

                    $remaining = max(0, 3 - $existing->count());

                    if ($remaining === 0) {
                        continue;
                    }

                    $alreadyOwned = Order::query()
                        ->where('user_id', $user->id)
                        ->whereIn('status', ['paid', 'completed'])
                        ->with('items:id,order_id,product_id')
                        ->get()
                        ->flatMap(fn ($order) => $order->items->pluck('product_id'))
                        ->unique()
                        ->values();

                    $products = Product::query()
                        ->where('is_published', true)
                        ->when($alreadyOwned->isNotEmpty(), fn ($q) => $q->whereNotIn('id', $alreadyOwned))
                        ->latest()
                        ->limit($remaining)
                        ->get();

                    foreach ($products as $product) {
                        $user->notify(new DigitalShopNotification(
                            'promotion',
                            'پیشنهاد امروز برای شما',
                            'محصول «' . $product->title . '» را ببینید؛ شاید برای شما مناسب باشد.',
                            route('product.show', $product),
                            'مشاهده محصول',
                            [
                                'kind' => 'daily_product_recommendation',
                                'product_id' => $product->id,
                                'date' => now()->toDateString(),
                            ],
                        ));

                        $sent++;
                    }
                }
            });

        return $sent;
    }
}
