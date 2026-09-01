<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {

            $table->id();

            // نام نمایشی / اطلاعات هویتی
            $table->string('name');

            $table->string('first_name', 100)
                ->nullable();

            $table->string('last_name', 100)
                ->nullable();

            // تنها شناسه ورود کاربر
            $table->string('phone', 20)
                ->unique();

            // اطلاعات هویتی
            $table->string('national_code', 10)
                ->nullable()
                ->unique();

            // وضعیت تأیید
            $table->timestamp('phone_verified_at')
                ->nullable();

            $table->timestamp('national_code_verified_at')
                ->nullable();

            // احراز هویت
            $table->string('password');

            // نقش کاربر
            $table->string('role', 30)
                ->default('customer')
                ->index();

            // وضعیت حساب
            $table->boolean('is_active')
                ->default(true)
                ->index();

            $table->rememberToken();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};