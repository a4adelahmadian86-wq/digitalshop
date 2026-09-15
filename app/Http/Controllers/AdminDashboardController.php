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
        $paidSales = Order::query()->whereIn('status', ['paid', 'completed'])->sum('total');
        $stats = [
            'sales' => $paidSales,
            'orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'products' => Product::count(),
            'users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'discounts' => DiscountCode::count(),
            'storage' => StorageProvider::where('is_active', true)->count(),
        ];

        $recentOrders = Order::with('user')->latest()->limit(8)->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'dashboardMode' => 'admin',
        ]);
    }
}