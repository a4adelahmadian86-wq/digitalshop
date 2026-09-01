<?php

namespace App\Services\Auth;

use App\Models\OtpSecurity;
use App\Services\Phone\IranPhoneValidator;
use App\Services\Sms\SmsManager;
use Illuminate\Support\Facades\Cache;
use RuntimeException;

class OtpService
{
    private const CODE_LENGTH = 6;

    private const EXPIRE_SECONDS = 120;

    private const RESEND_SECONDS = 120;

    private const DAILY_LIMIT_NORMAL = 3;

    private const DAILY_LIMIT_RESTRICTED = 2;

    private const DAILY_LIMIT_SEVERE = 1;

    public function __construct(
        private SmsManager $sms,
        private IranPhoneValidator $phoneValidator
    ) {
    }

    public function send(
        string $phone
    ): bool {

        /*
         * نرمال‌سازی و اعتبارسنجی شماره
         * قبل از رسیدن به SmsManager
         */
        $phone = $this->phoneValidator->normalize(
            $phone
        );

        if (!$this->phoneValidator->isValid($phone)) {
            throw new RuntimeException(
                'شماره موبایل وارد شده معتبر نیست.'
            );
        }

        $security = OtpSecurity::firstOrCreate(
            [
                'phone' => $phone,
            ],
            [
                'daily_count_date' => now()->toDateString(),
            ]
        );

        /*
         * اگر قبلاً یک ماه مسدود شده
         */
        if (
            $security->sms_blocked_until &&
            now()->lt(
                $security->sms_blocked_until
            )
        ) {
            throw new RuntimeException(
                'ارسال پیامک برای این حساب موقتاً متوقف شده است.'
            );
        }

        /*
         * Reset daily counter
         */
        if (
            !$security->daily_count_date ||
            !$security->daily_count_date->isToday()
        ) {
            $security->daily_sent_count = 0;

            $security->daily_count_date =
                now()->toDateString();

            $security->save();
        }

        /*
         * محدودیت ۱۲۰ ثانیه
         */
        if (
            $security->last_sent_at &&
            now()->lt(
                $security->last_sent_at
                    ->copy()
                    ->addSeconds(
                        self::RESEND_SECONDS
                    )
            )
        ) {
            $remaining = now()->diffInSeconds(
                $security->last_sent_at
                    ->copy()
                    ->addSeconds(
                        self::RESEND_SECONDS
                    )
            );

            throw new RuntimeException(
                "لطفاً {$remaining} ثانیه دیگر صبر کنید."
            );
        }

        /*
         * تعیین سقف روزانه
         */
        $dailyLimit =
            match (
                $security->restriction_level
            ) {
                0 => self::DAILY_LIMIT_NORMAL,
                1 => self::DAILY_LIMIT_RESTRICTED,
                default => self::DAILY_LIMIT_SEVERE,
            };

        if (
            $security->daily_sent_count
            >= $dailyLimit
        ) {
            throw new RuntimeException(
                'سقف ارسال پیامک امروز برای شما تکمیل شده است.'
            );
        }

        /*
         * جلوگیری از race condition ساده
         */
        $lock = Cache::lock(
            'otp-send:' . $phone,
            5
        );

        if (!$lock->get()) {
            throw new RuntimeException(
                'درخواست دیگری در حال پردازش است. لطفاً کمی صبر کنید.'
            );
        }

        try {

            /*
             * تولید OTP
             */
            $code = (string) random_int(
                100000,
                999999
            );

            /*
             * ذخیره امن OTP
             */
            Cache::put(
                'otp:code:' . $phone,
                hash(
                    'sha256',
                    $code
                ),
                self::EXPIRE_SECONDS
            );

            /*
             * زمان انقضای OTP
             */
            Cache::put(
                'otp:expires:' . $phone,
                now()
                    ->addSeconds(
                        self::EXPIRE_SECONDS
                    )
                    ->timestamp,
                self::EXPIRE_SECONDS
            );

            /*
             * ارسال SMS
             */
            $sent = $this->sms->sendOtp(
                $phone,
                $code
            );

            if (!$sent) {

                Cache::forget(
                    'otp:code:' . $phone
                );

                Cache::forget(
                    'otp:expires:' . $phone
                );

                throw new RuntimeException(
                    'ارسال پیامک انجام نشد.'
                );
            }

            /*
             * ثبت موفق ارسال
             */
            $security->last_sent_at = now();

            $security->daily_sent_count++;

            $security->last_ip =
                request()->ip();

            $security->save();

            return true;

        } finally {

            optional($lock)->release();
        }
    }

    public function verify(
        string $phone,
        string $code
    ): bool {

        $phone = $this->phoneValidator->normalize(
            $phone
        );

        if (!$this->phoneValidator->isValid($phone)) {
            return false;
        }

        $stored = Cache::get(
            'otp:code:' . $phone
        );

        if (!$stored) {
            return false;
        }

        $hash = hash(
            'sha256',
            trim($code)
        );

        if (!hash_equals(
            $stored,
            $hash
        )) {
            return false;
        }

        /*
         * OTP یکبار مصرف است.
         */
        Cache::forget(
            'otp:code:' . $phone
        );

        Cache::forget(
            'otp:expires:' . $phone
        );

        return true;
    }

    /*
     * ارسال OTP برای بازیابی رمز عبور
     *
     * فعلاً از همان سیستم امن OTP اصلی
     * استفاده می‌کند تا محدودیت‌های ارسال
     * و اتصال SMS یکپارچه باقی بماند.
     */
    public function sendPasswordResetOtp(
        string $phone
    ): bool {

        return $this->send(
            $phone
        );
    }

    /*
     * بررسی OTP بازیابی رمز عبور
     */
    public function verifyPasswordResetOtp(
        string $phone,
        string $code
    ): bool {

        return $this->verify(
            $phone,
            $code
        );
    }
}