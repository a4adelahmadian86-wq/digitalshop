<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Auth\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PasswordController extends Controller
{
    public function __construct(
        private OtpService $otp
    ) {
    }

    /**
     * نمایش صفحه فراموشی رمز
     */
    public function request()
    {
        return view('auth.forgot-password');
    }

    /**
     * ارسال OTP فراموشی رمز
     */
    public function send(Request $request)
    {
        $data = $request->validate([
            'phone' => [
                'required',
                'string',
                'regex:/^09\d{9}$/',
            ],
        ]);

        $phone = $this->otp->normalizePhone(
            $data['phone']
        );

        $user = User::where(
            'phone',
            $phone
        )->first();

        /*
         * عمداً مشخص نمی‌کنیم شماره وجود دارد یا نه.
         * این کار از Phone Enumeration جلوگیری می‌کند.
         */
        if (!$user) {
            return back()->with(
                'success',
                'اگر حسابی با این شماره وجود داشته باشد، کد تأیید ارسال خواهد شد.'
            );
        }

        /*
         * محدودیت اقتصادی مخصوص Forgot Password
         */
        $this->otp->sendPasswordResetOtp(
            $phone
        );

        $request->session()->put(
            'password_reset.phone',
            $phone
        );

        return redirect()
            ->route('password.verify')
            ->with(
                'success',
                'کد تأیید برای شماره شما ارسال شد.'
            );
    }

    /**
     * صفحه ورود OTP
     */
    public function showVerify(Request $request)
    {
        $phone = $request->session()->get(
            'password_reset.phone'
        );

        if (!$phone) {
            return redirect()->route(
                'password.request'
            );
        }

        return view(
            'auth.forgot-password-verify',
            [
                'phone' => $phone,
                'seconds' => (int) env(
                    'OTP_RESEND_SECONDS',
                    120
                ),
            ]
        );
    }

    /**
     * تأیید OTP
     */
    public function verify(Request $request)
    {
        $data = $request->validate([
            'phone' => [
                'required',
                'string',
                'regex:/^09\d{9}$/',
            ],

            'code' => [
                'required',
                'digits:6',
            ],
        ]);

        $phone = $this->otp->normalizePhone(
            $data['phone']
        );

        $verified = $this->otp->verifyPasswordResetOtp(
            $phone,
            $data['code']
        );

        if (!$verified) {
            throw ValidationException::withMessages([
                'code' =>
                    'کد تأیید نادرست یا منقضی شده است.',
            ]);
        }

        $request->session()->put(
            'password_reset.verified',
            true
        );

        return view(
            'auth.reset-password',
            [
                'phone' => $phone,
            ]
        );
    }

    /**
     * تغییر رمز
     */
    public function reset(Request $request)
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
                'min:8',
                'confirmed',
            ],
        ]);

        $phone = $this->otp->normalizePhone(
            $data['phone']
        );

        if (
            !$request->session()->get(
                'password_reset.verified'
            )
        ) {
            return redirect()
                ->route('password.request')
                ->withErrors([
                    'phone' =>
                        'ابتدا شماره موبایل خود را تأیید کنید.',
                ]);
        }

        $user = User::where(
            'phone',
            $phone
        )->firstOrFail();

        $user->update([
            'password' => Hash::make(
                $data['password']
            ),
        ]);

        /*
         * OTP و وضعیت Reset بعد از استفاده پاک شوند.
         */
        $request->session()->forget([
            'password_reset',
        ]);

        return redirect()
            ->route('login')
            ->with(
                'success',
                'رمز عبور با موفقیت تغییر کرد. اکنون می‌توانید وارد شوید.'
            );
    }
}