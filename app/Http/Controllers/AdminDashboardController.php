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
        $viewer = request()->user();
        $canUsers = $viewer->hasPermission('users.view');
        $canProducts = $viewer->hasPermission('products.view');
        $canOrders = $viewer->hasPermission('orders.view');
        $canPayments = $viewer->hasPermission('payments.view');
        $canStorage = $viewer->hasPermission('storage.manage');
        $canDiscounts = $viewer->hasPermission('discounts.manage');

        $stats = [
            'sales' => $canPayments ? Order::whereIn('status', ['paid', 'completed'])->sum('total') : null,
            'orders' => $canOrders ? Order::count() : null,
            'pending_orders' => $canOrders ? Order::where('status', 'pending')->count() : null,
            'products' => $canProducts ? Product::count() : null,
            'users' => $canUsers ? User::count() : null,
            'active_users' => $canUsers ? User::where('is_active', true)->count() : null,
            'discounts' => $canDiscounts ? DiscountCode::count() : null,
            'storage' => $canStorage ? StorageProvider::where('is_active', true)->count() : null,
        ];

        $recentOrders = $canOrders ? Order::with($canUsers ? 'user' : [])->latest()->limit(8)->get() : collect();

        return view('admin.dashboard-rbac', compact('stats', 'recentOrders') + ['dashboardMode' => 'admin']);
    }
}