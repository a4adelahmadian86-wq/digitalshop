<?php

namespace App\Http\Controllers;

use App\Services\Auth\OtpService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OtpController extends Controller
{
    public function __construct(
        private OtpService $otp
    ) {
    }


    /**
     * نمایش صفحه OTP
     */
    public function show(
        Request $request
    ) {
        $phone =
            $request->session()->get(
                'otp.phone'
            );

        if (!$phone) {
            return redirect()
                ->route('login');
        }

        return view(
            'auth.otp',
            [
                'phone' => $phone,

                'seconds' => (int) env(
                    'OTP_RESEND_SECONDS',
                    120
                ),

                'purpose' =>
                    $request->session()->get(
                        'otp.purpose',
                        'register'
                    ),
            ]
        );
    }


    /**
     * ارسال OTP
     */
    public function send(
        Request $request
    ) {
        $data = $request->validate(
            [
                'phone' => [
                    'required',
                    'string',
                    'regex:/^09\d{9}$/',
                ],
            ],
            [
                'phone.required' =>
                    'شماره موبایل الزامی است.',

                'phone.string' =>
                    'شماره موبایل معتبر نیست.',

                'phone.regex' =>
                    'شماره موبایل باید ۱۱ رقمی و با 09 شروع شود.',
            ]
        );

        try {

            /*
             * شماره از قبل با فرمت 09xxxxxxxxx
             * اعتبارسنجی شده است.
             *
             * نیازی به normalizePhone() نیست.
             */
            $phone = $data['phone'];

            $this->otp->send(
                $phone
            );

            $request->session()->put([
                'otp.phone' =>
                    $phone,

                'otp.purpose' =>
                    $request->session()->get(
                        'otp.purpose',
                        'register'
                    ),
            ]);

            return redirect()
                ->route('otp.show')
                ->with(
                    'success',
                    'کد تأیید با موفقیت ارسال شد.'
                );

        } catch (\Throwable $e) {

            return back()
                ->withErrors([
                    'phone' =>
                        $e->getMessage(),
                ])
                ->withInput();
        }
    }


    /**
     * تأیید OTP
     */
    public function verify(
        Request $request
    ) {
        $data = $request->validate(
            [
                'phone' => [
                    'required',
                    'string',
                    'regex:/^09\d{9}$/',
                ],

                'code' => [
                    'required',
                    'digits:6',
                ],
            ],
            [
                'phone.required' =>
                    'شماره موبایل الزامی است.',

                'phone.string' =>
                    'شماره موبایل معتبر نیست.',

                'phone.regex' =>
                    'شماره موبایل باید ۱۱ رقمی و با 09 شروع شود.',

                'code.required' =>
                    'کد تأیید را وارد کنید.',

                'code.digits' =>
                    'کد تأیید باید ۶ رقمی باشد.',
            ]
        );

        $phone =
            $data['phone'];

        $verified =
            $this->otp->verify(
                $phone,
                $data['code']
            );

        if (!$verified) {

            throw ValidationException::withMessages([
                'code' =>
                    'کد تأیید نادرست یا منقضی شده است.',
            ]);
        }

        $purpose =
            $request->session()->get(
                'otp.purpose',
                'register'
            );


        /*
         * ثبت تأیید موفق
         */
        $request->session()->put([
            'otp.phone' =>
                $phone,

            'otp.verified' =>
                true,
        ]);


        /*
         * ثبت‌نام
         */
        if ($purpose === 'register') {

            return redirect()
                ->route(
                    'register',
                    [
                        'phone' => $phone,
                    ]
                )
                ->with(
                    'success',
                    'شماره موبایل با موفقیت تأیید شد.'
                );
        }


        /*
         * ورود حساب موجود
         * ولی شماره هنوز تأیید نشده است.
         */
        if ($purpose === 'login') {

            $request->session()->put(
                'login.phone',
                $phone
            );

            return redirect()
                ->route(
                    'login.password'
                )
                ->with(
                    'success',
                    'شماره موبایل با موفقیت تأیید شد. اکنون رمز عبور خود را وارد کنید.'
                );
        }


        /*
         * بازیابی رمز عبور
         */
        if (
            $purpose === 'password_reset'
        ) {

            $request->session()->put(
                'password_reset.verified',
                true
            );

            return redirect()
                ->route(
                    'password.reset'
                );
        }


        /*
         * مقصد پیش‌فرض امن
         */
        return redirect()
            ->route('home');
    }


    /**
     * ارسال مجدد OTP
     */
    public function resend(
        Request $request
    ) {
        $data = $request->validate(
            [
                'phone' => [
                    'required',
                    'string',
                    'regex:/^09\d{9}$/',
                ],
            ],
            [
                'phone.required' =>
                    'شماره موبایل الزامی است.',

                'phone.string' =>
                    'شماره موبایل معتبر نیست.',

                'phone.regex' =>
                    'شماره موبایل باید ۱۱ رقمی و با 09 شروع شود.',
            ]
        );

        try {

            $phone =
                $data['phone'];

            $this->otp->send(
                $phone
            );

            $request->session()->put(
                'otp.phone',
                $phone
            );

            /*
             * ارسال مجدد یعنی تأیید قبلی دیگر معتبر نیست.
             */
            $request->session()->forget(
                'otp.verified'
            );

            return redirect()
                ->route('otp.show')
                ->with(
                    'success',
                    'کد تأیید جدید با موفقیت ارسال شد.'
                );

        } catch (\Throwable $e) {

            return back()
                ->withErrors([
                    'phone' =>
                        $e->getMessage(),
                ])
                ->withInput();
        }
    }
}
