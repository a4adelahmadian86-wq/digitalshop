<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * نمایش صفحه ورود با شماره موبایل
     */
    public function phone()
    {
        return view('admin.auth.phone');
    }

    /**
     * بررسی شماره موبایل ادمین
     */
    public function checkPhone(Request $request)
    {
        $request->validate([
            'phone' => [
                'required',
                'regex:/^09[0-9]{9}$/',
            ],
        ]);

        $admin = Admin::where('phone', $request->phone)
            ->where('is_active', true)
            ->first();

        if (!$admin) {
            return back()->withErrors([
                'phone' => 'این شماره به حساب مدیریت دسترسی ندارد.',
            ]);
        }

        session([
            'admin_phone' => $admin->phone,
        ]);

        return redirect()->route('admin.login');
    }

    /**
     * نمایش صفحه ورود رمز عبور
     */
    public function login()
    {
        if (!session('admin_phone')) {
            return redirect()->route('admin.phone');
        }

        return view('admin.auth.login');
    }

    /**
     * ورود نهایی ادمین
     */
    public function loginStore(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('phone', session('admin_phone'))
            ->where('username', $request->username)
            ->where('is_active', true)
            ->first();

        if (
            !$admin ||
            !Hash::check(
                $request->password,
                $admin->password
            )
        ) {
            return back()
                ->withErrors([
                    'login' => 'نام کاربری یا رمز عبور صحیح نیست.',
                ])
                ->withInput(
                    $request->only('username')
                );
        }

        auth('admin')->login($admin);

        session()->forget('admin_phone');

        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }

    /**
     * خروج ادمین
     */
    public function logout(Request $request)
    {
        auth('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.phone');
    }
}