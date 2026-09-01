<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * صفحه ورود
     */
    public function login()
    {
        return view('auth.login');
    }


    /**
     * بررسی وجود شماره موبایل
     *
     * این متد فقط وجود حساب را بررسی می‌کند.
     * OTP در این مرحله ارسال نمی‌شود.
     */
    public function checkPhone(Request $request)
    {
        $data = $request->validate([
            'phone' => [
                'required',
                'string',
                'regex:/^09\d{9}$/',
            ],
        ], [
            'phone.required' =>
                'شماره موبایل الزامی است.',

            'phone.regex' =>
                'شماره موبایل معتبر نیست. شماره باید ۱۱ رقمی و با ۰۹ شروع شود.',
        ]);

        $phone = $this->normalizePhone(
            $data['phone']
        );

        $user = User::where(
            'phone',
            $phone
        )->first();

        return response()->json([
            'success' => true,
            'exists' => (bool) $user,
        ]);
    }


    /**
     * ورود با شماره موبایل + رمز عبور
     */
    public function loginStore(Request $request)
    {
        $data = $request->validate([
            'phone' => [
                'required',
                'string',
                'regex:/^09\d{9}$/',
            ],

            'password' => [
                'required',
                'string',
            ],
        ], [
            'phone.required' =>
                'شماره موبایل الزامی است.',

            'phone.regex' =>
                'شماره موبایل معتبر نیست. شماره باید ۱۱ رقمی و با ۰۹ شروع شود.',

            'password.required' =>
                'رمز عبور را وارد کنید.',
        ]);

        $phone = $this->normalizePhone(
            $data['phone']
        );

        $user = User::where(
            'phone',
            $phone
        )->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'phone' =>
                    'حسابی با این شماره پیدا نشد.',
            ]);
        }

        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'phone' =>
                    'حساب کاربری شما غیرفعال است.',
            ]);
        }

        if (!$user->phone_verified_at) {

            $request->session()->put([
                'otp.phone' => $phone,
                'otp.purpose' => 'login',
            ]);

            return redirect()
                ->route('otp.show')
                ->with(
                    'warning',
                    'ابتدا شماره موبایل خود را تأیید کنید.'
                );
        }

        if (
            empty($user->password) ||
            !Hash::check(
                $data['password'],
                $user->password
            )
        ) {
            throw ValidationException::withMessages([
                'password' =>
                    'رمز عبور واردشده صحیح نیست.',
            ]);
        }

        Auth::login(
            $user,
            $request->boolean('remember')
        );

        $request->session()->regenerate();

        $request->session()->forget([
            'login.phone',
            'otp.phone',
            'otp.verified',
            'otp.purpose',
        ]);

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended(
            route('home')
        );
    }


    /**
     * صفحه ورود رمز عبور
     */
    public function passwordForm(Request $request)
    {
        $phone = $request->session()->get(
            'login.phone'
        );

        if (!$phone) {
            return redirect()
                ->route('login');
        }

        return view(
            'auth.password',
            [
                'phone' => $phone,
            ]
        );
    }


    /**
     * ورود نهایی از صفحه رمز عبور
     */
    public function passwordLogin(Request $request)
    {
        $data = $request->validate([
            'phone' => [
                'required',
                'string',
                'regex:/^09\d{9}$/',
            ],

            'password' => [
                'required',
                'string',
            ],
        ], [
            'phone.required' =>
                'شماره موبایل الزامی است.',

            'phone.regex' =>
                'شماره موبایل معتبر نیست. شماره باید ۱۱ رقمی و با ۰۹ شروع شود.',

            'password.required' =>
                'رمز عبور را وارد کنید.',
        ]);

        $phone = $this->normalizePhone(
            $data['phone']
        );

        $user = User::where(
            'phone',
            $phone
        )->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'phone' =>
                    'حسابی با این شماره پیدا نشد.',
            ]);
        }

        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'phone' =>
                    'حساب کاربری شما غیرفعال است.',
            ]);
        }

        if (!$user->phone_verified_at) {

            $request->session()->put([
                'otp.phone' => $phone,
                'otp.purpose' => 'login',
            ]);

            return redirect()
                ->route('otp.show')
                ->with(
                    'warning',
                    'ابتدا شماره موبایل خود را تأیید کنید.'
                );
        }

        if (
            empty($user->password) ||
            !Hash::check(
                $data['password'],
                $user->password
            )
        ) {
            throw ValidationException::withMessages([
                'password' =>
                    'رمز عبور صحیح نیست.',
            ]);
        }

        Auth::login(
            $user,
            $request->boolean('remember')
        );

        $request->session()->regenerate();

        $request->session()->forget([
            'login.phone',
            'otp.phone',
            'otp.verified',
            'otp.purpose',
        ]);

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended(
            route('home')
        );
    }


    /**
     * صفحه ثبت‌نام
     */
    public function register(Request $request)
    {
        $phone =
            $request->query('phone')
            ?: $request->session()->get(
                'otp.phone'
            );

        if (!$phone) {
            return redirect()
                ->route('login');
        }

        $phone = $this->normalizePhone(
            $phone
        );

        if (
            !$request->session()->get(
                'otp.verified'
            )
        ) {
            return redirect()
                ->route('login')
                ->with(
                    'warning',
                    'ابتدا شماره موبایل خود را تأیید کنید.'
                );
        }

        return view(
            'auth.register',
            [
                'phone' => $phone,
            ]
        );
    }


    /**
     * ساخت حساب
     */
    public function registerStore(Request $request)
    {
        $phone =
            $request->session()->get(
                'otp.phone'
            );

        $otpVerified =
            $request->session()->get(
                'otp.verified'
            );

        if (
            !$phone ||
            !$otpVerified
        ) {
            return redirect()
                ->route('login')
                ->with(
                    'warning',
                    'فرآیند تأیید شماره کامل نشده است.'
                );
        }

        $phone = $this->normalizePhone(
            $phone
        );

        if (
            User::where(
                'phone',
                $phone
            )->exists()
        ) {
            return redirect()
                ->route('login')
                ->with(
                    'warning',
                    'این شماره قبلاً دارای حساب کاربری است.'
                );
        }

        $data = $request->validate([
            'first_name' => [
                'required',
                'string',
                'min:2',
                'max:100',
            ],

            'last_name' => [
                'required',
                'string',
                'min:2',
                'max:100',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'first_name.required' =>
                'نام الزامی است.',

            'first_name.min' =>
                'نام باید حداقل ۲ کاراکتر باشد.',

            'last_name.required' =>
                'نام خانوادگی الزامی است.',

            'last_name.min' =>
                'نام خانوادگی باید حداقل ۲ کاراکتر باشد.',

            'email.email' =>
                'ایمیل واردشده معتبر نیست.',

            'email.unique' =>
                'این ایمیل قبلاً استفاده شده است.',

            'password.required' =>
                'رمز عبور الزامی است.',

            'password.min' =>
                'رمز عبور باید حداقل ۸ کاراکتر باشد.',

            'password.confirmed' =>
                'تکرار رمز عبور صحیح نیست.',
        ]);

        $user = User::create([
            'phone' =>
                $phone,

            'first_name' =>
                $data['first_name'],

            'last_name' =>
                $data['last_name'],

            'email' =>
                $data['email'] ?? null,

            'password' =>
                $data['password'],

            'role' =>
                'buyer',

            'is_active' =>
                true,

            'phone_verified_at' =>
                now(),
        ]);

        $request->session()->forget([
            'otp.phone',
            'otp.verified',
            'otp.purpose',
            'login.phone',
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->intended(
            route('home')
        );
    }


    /**
     * خروج
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('home');
    }


    /**
     * نرمال‌سازی شماره موبایل
     */
    private function normalizePhone(
        string $phone
    ): string {
        $phone = strtr(
            $phone,
            [
                '۰' => '0',
                '۱' => '1',
                '۲' => '2',
                '۳' => '3',
                '۴' => '4',
                '۵' => '5',
                '۶' => '6',
                '۷' => '7',
                '۸' => '8',
                '۹' => '9',

                '٠' => '0',
                '١' => '1',
                '٢' => '2',
                '٣' => '3',
                '٤' => '4',
                '٥' => '5',
                '٦' => '6',
                '٧' => '7',
                '٨' => '8',
                '٩' => '9',
            ]
        );

        return preg_replace(
            '/\D/',
            '',
            $phone
        );
    }
}