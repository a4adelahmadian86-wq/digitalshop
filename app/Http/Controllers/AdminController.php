<?php

namespace App\Http\Controllers;

use App\Models\DiscountCode;
use App\Models\Order;
use App\Models\Product;
use App\Models\StorageAccount;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'sales' => Order::whereIn(
                'status',
                ['paid', 'completed']
            )->sum('total'),

            'orders' => Order::count(),

            'products' => Product::count(),

            'users' => User::count(),

            'discounts' => DiscountCode::count(),
        ];

        $storage = StorageAccount::where(
            'is_active',
            true
        )->get();

        $storageCapacity =
            $storage->sum('capacity_bytes');

        $storageUsed =
            $storage->sum('used_bytes');

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