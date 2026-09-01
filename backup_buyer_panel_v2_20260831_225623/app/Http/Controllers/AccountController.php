<?php

namespace App\Http\Controllers;

use App\Models\Download;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = $request->user();

        $ordersCount = Order::where('user_id', $user->id)->count();
        $paidOrdersCount = Order::where('user_id', $user->id)->where('status', 'paid')->count();
        $downloadsCount = Download::where('user_id', $user->id)->count();
        $recentOrders = Order::with('items.product')
            ->where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('account.dashboard', compact(
            'user', 'ordersCount', 'paidOrdersCount', 'downloadsCount', 'recentOrders'
        ));
    }

    public function orders(Request $request)
    {
        $orders = Order::with('items.product')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('account.orders', compact('orders'));
    }

    public function order(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 404);
        $order->load('items.product');
        return view('account.order-show', compact('order'));
    }

    public function files(Request $request)
    {
        $files = Download::with(['product', 'orderItem.order'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('account.files', compact('files'));
    }

    public function wallet(Request $request)
    {
        $user = $request->user();
        $wallet = $user->wallet;
        $topups = $user->walletTopups()->latest()->paginate(15);
        return view('account.wallet', compact('wallet', 'topups'));
    }

    public function profile(Request $request)
    {
        return view('account.profile', ['user' => $request->user()]);
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
        ], [
            'first_name.required' => 'نام الزامی است.',
        ]);

        $request->user()->update($data);

        return back()->with('success', 'اطلاعات پروفایل ذخیره شد.');
    }

    public function security(Request $request)
    {
        return view('account.security');
    }

    public function updateSecurity(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'رمز عبور فعلی الزامی است.',
            'current_password.current_password' => 'رمز عبور فعلی صحیح نیست.',
            'password.min' => 'رمز عبور جدید باید حداقل ۸ کاراکتر باشد.',
            'password.confirmed' => 'تکرار رمز عبور صحیح نیست.',
        ]);

        $request->user()->update(['password' => Hash::make($data['password'])]);
        return back()->with('success', 'رمز عبور با موفقیت تغییر کرد.');
    }

    public function notifications(Request $request)
    {
        $notifications = $request->user()->notifications()->latest()->paginate(20);
        return view('account.notifications', compact('notifications'));
    }

    public function readNotification(Request $request, string $id)
    {
        $notification = $request->user()->notifications()->whereKey($id)->firstOrFail();
        $notification->markAsRead();
        return back();
    }
}
