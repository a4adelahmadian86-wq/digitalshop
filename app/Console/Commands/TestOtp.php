<?php

namespace App\Console\Commands;

use App\Services\Auth\OtpService;
use Illuminate\Console\Command;
use Throwable;

class TestOtp extends Command
{
    protected $signature = 'sms:test {phone}';

    protected $description = 'Send a test OTP SMS';

    public function handle(
        OtpService $otp
    ): int {
        $phone = $this->argument('phone');

        try {
            $otp->send($phone);

            $this->info(
                'OTP sent successfully.'
            );

            return self::SUCCESS;

        } catch (Throwable $e) {

            $this->error(
                $e->getMessage()
            );

            return self::FAILURE;
        }
    }
}