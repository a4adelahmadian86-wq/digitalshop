<?php

namespace App\Http\Controllers;

use App\Models\DiscountCode;
use App\Models\Order;
use App\Models\Product;
use App\Models\StorageProvider;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $sales = Order::query()
            ->where('status', 'paid')
            ->sum('total');

        $orders = Order::count();

        $products = Product::count();

        $users = User::count();

        $discounts = DiscountCode::count();

        $storage = StorageProvider::query()
            ->where('is_active', true)
            ->withCount('products')
            ->latest()
            ->get();

        $storageCapacity = 0;

        $storageUsed = 0;

        foreach ($storage as $provider) {

            /*
             * در معماری فعلی StorageProvider
             * ظرفیت و مصرف در خود Provider ذخیره نمی‌شود.
             *
             * بنابراین فعلاً مقدار Storage را
             * صفر نگه می‌داریم تا زمانی که
             * Storage Account واقعی اضافه شود.
             */
        }

        $stats = [
            'sales' => $sales,
            'orders' => $orders,
            'products' => $products,
            'users' => $users,
            'discounts' => $discounts,
        ];

        return view(
            'admin.dashboard',
            compact(
                'stats',
                'storage',
                'storageCapacity',
                'storageUsed'
            )
        );
    }
}
