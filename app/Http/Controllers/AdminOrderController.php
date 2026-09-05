<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $status = trim((string) $request->input('status', ''));
        $q = trim((string) $request->input('q', ''));

        $orders = Order::with(['user', 'items.product', 'payment'])
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($inner) use ($q) {
                    $inner->where('order_number', 'like', '%'.$q.'%')
                        ->orWhereHas('user', fn ($user) => $user->where('first_name', 'like', '%'.$q.'%')->orWhere('last_name', 'like', '%'.$q.'%')->orWhere('phone', 'like', '%'.$q.'%'));
                });
            })
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'paid' => Order::where('status', 'paid')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'failed' => Order::where('status', 'failed')->count(),
            'revenue' => (int) Order::whereIn('status', ['paid', 'completed'])->sum('total'),
        ];

        return view('admin.orders.index', compact('orders', 'stats', 'status', 'q'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product.files', 'payment', 'invoice']);
        return view('admin.orders.show', compact('order'));
    }

    public function status(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['pending', 'paid', 'completed', 'failed', 'cancelled', 'refunded'])],
        ]);

        $order->update(['status' => $data['status']]);

        return back()->with('success', 'وضعیت سفارش #'.$order->order_number.' بروزرسانی شد.');
    }
}
