<?php

namespace App\Http\Controllers;

use App\Models\DownloadLog;
use App\Models\OrderItem;
use App\Services\Storage\StorageManager;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class DownloadController extends Controller
{
    public function download(
        OrderItem $item,
        StorageManager $storageManager
    ) {
        $user = auth()->user();

        /*
         * کاربر باید وارد شده باشد.
         */
        if (!$user) {
            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'برای دانلود فایل ابتدا وارد حساب کاربری شوید.'
                );
        }

        /*
         * مالکیت سفارش
         */
        if (
            !$item->order ||
            $item->order->user_id !== $user->id
        ) {
            return $this->deny(
                $item,
                'unauthorized'
            );
        }

        /*
         * سفارش باید پرداخت شده باشد.
         */
        if ($item->order->status !== 'paid') {
            return $this->deny(
                $item,
                'unpaid'
            );
        }

        /*
         * Rate Limit برای کاربر
         */
        $key =
            'download:user:' .
            $user->id .
            ':item:' .
            $item->id;

        if (
            RateLimiter::tooManyAttempts(
                $key,
                (int) env(
                    'DOWNLOAD_DAILY_LIMIT',
                    20
                )
            )
        ) {
            return $this->limited($item);
        }

        /*
         * Rate Limit برای IP
         */
        $ipKey =
            'download:ip:' .
            request()->ip();

        if (
            RateLimiter::tooManyAttempts(
                $ipKey,
                (int) env(
                    'DOWNLOAD_IP_DAILY_LIMIT',
                    100
                )
            )
        ) {
            return $this->limited($item);
        }

        $product = $item->product;

        /*
         * محصول وجود ندارد.
         */
        if (!$product) {
            return $this->deny(
                $item,
                'product_missing'
            );
        }

        /*
         * محصول باید فایل داشته باشد.
         */
        if (!$product->storage_path) {

            Log::error(
                'Purchased product has no storage path.',
                [
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'order_item_id' => $item->id,
                ]
            );

            $this->log(
                $item,
                'missing'
            );

            return back()->with(
                'error',
                'فایل این محصول در Storage ثبت نشده است.'
            );
        }

        /*
         * Storage Provider
         */
        $storageProvider =
            $product->storageProvider;

        if (!$storageProvider) {

            Log::error(
                'Product has no storage provider.',
                [
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'order_item_id' => $item->id,
                ]
            );

            $this->log(
                $item,
                'missing'
            );

            return back()->with(
                'error',
                'Storage Provider این محصول پیدا نشد.'
            );
        }

        /*
         * Provider باید فعال باشد.
         */
        if (!$storageProvider->is_active) {

            Log::error(
                'Product storage provider is inactive.',
                [
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'storage_provider_id' =>
                        $storageProvider->id,
                ]
            );

            $this->log(
                $item,
                'missing'
            );

            return back()->with(
                'error',
                'Storage فایل در حال حاضر فعال نیست.'
            );
        }

        /*
         * Provider مربوط به محصول را دریافت می‌کنیم.
         */
        try {

            $provider =
                $storageManager->provider(
                    $storageProvider
                );

        } catch (\Throwable $e) {

            report($e);

            Log::error(
                'Could not resolve storage provider.',
                [
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'storage_provider_id' =>
                        $storageProvider->id,
                    'error' => $e->getMessage(),
                ]
            );

            $this->log(
                $item,
                'missing'
            );

            return back()->with(
                'error',
                'Storage Provider فایل قابل استفاده نیست.'
            );
        }

        /*
         * فایل باید واقعاً در Provider وجود داشته باشد.
         */
        if (
            !$provider->exists(
                $product->storage_path
            )
        ) {

            Log::error(
                'Purchased file is missing from storage.',
                [
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'order_item_id' => $item->id,
                    'storage_provider_id' =>
                        $storageProvider->id,
                    'storage_path' =>
                        $product->storage_path,
                ]
            );

            $this->log(
                $item,
                'missing'
            );

            return back()->with(
                'error',
                'فایل خریداری‌شده در حال حاضر در دسترس نیست.'
            );
        }

        /*
         * فقط بعد از اینکه همه کنترل‌های دسترسی
         * و وجود فایل موفق شدند Rate Limit را ثبت می‌کنیم.
         */
        RateLimiter::hit(
            $key,
            86400
        );

        RateLimiter::hit(
            $ipKey,
            86400
        );

        /*
         * ثبت دانلود موفق
         */
        $this->log(
            $item,
            'success'
        );

        /*
         * دانلود از همان Storage Provider
         * که فایل روی آن ذخیره شده است.
         */
        return $provider->download(
            $product->storage_path,
            $product->file_name
        );
    }

    private function deny(
        $item,
        string $reason
    ) {
        $this->log(
            $item,
            'denied'
        );

        Log::warning(
            'Unauthorized download attempt.',
            [
                'user_id' => auth()->id(),
                'order_item_id' => $item->id,
                'reason' => $reason,
                'ip' => request()->ip(),
            ]
        );

        return back()->with(
            'error',
            'شما دسترسی مجاز به این فایل را ندارید.'
        );
    }

    private function limited(
        $item
    ) {
        $this->log(
            $item,
            'limited'
        );

        Log::warning(
            'Download rate limit exceeded.',
            [
                'user_id' => auth()->id(),
                'order_item_id' => $item->id,
                'ip' => request()->ip(),
            ]
        );

        return back()->with(
            'error',
            'تعداد دانلود روزانه این فایل به حد مجاز رسیده است. لطفاً بعداً دوباره تلاش کنید.'
        );
    }

    private function log(
        $item,
        string $status
    ) {
        DownloadLog::create([
            'user_id' => auth()->id(),
            'order_item_id' => $item->id,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'status' => $status,
        ]);
    }
}