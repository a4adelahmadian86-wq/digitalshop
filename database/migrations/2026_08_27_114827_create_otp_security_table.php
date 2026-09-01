<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('otp_security', function (Blueprint $table) {

            $table->id();

            /*
             * شماره موبایل به صورت نرمال‌شده
             */
            $table->string('phone', 20)->unique();

            /*
             * آخرین زمانی که OTP ارسال شده
             */
            $table->timestamp('last_sent_at')->nullable();

            /*
             * تعداد OTP ارسال‌شده در روز جاری
             */
            $table->unsignedTinyInteger(
                'daily_sent_count'
            )->default(0);

            /*
             * تاریخ مربوط به daily_sent_count
             */
            $table->date('daily_count_date')->nullable();

            /*
             * تعداد login موفقی که بدون فاصله
             * با logout تکرار شده‌اند.
             */
            $table->unsignedTinyInteger(
                'login_logout_streak'
            )->default(0);

            /*
             * آخرین زمان login موفق
             */
            $table->timestamp('last_login_at')->nullable();

            /*
             * آخرین زمان logout
             */
            $table->timestamp('last_logout_at')->nullable();

            /*
             * اگر کاربر به دلیل login/logout
             * مکرر یک ماه مسدود شد.
             */
            $table->timestamp(
                'sms_blocked_until'
            )->nullable();

            /*
             * سطح محدودیت:
             *
             * 0 = عادی
             * 1 = محدودیت روز بعد
             * 2 = محدودیت شدید تا یک هفته
             * 3 = مسدودیت یک ماهه
             */
            $table->unsignedTinyInteger(
                'restriction_level'
            )->default(0);

            /*
             * برای کنترل IP
             */
            $table->string(
                'last_ip',
                45
            )->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'otp_security'
        );
    }
};