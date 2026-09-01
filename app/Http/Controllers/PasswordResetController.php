<?php

namespace App\Http\Controllers;

use App\Services\Auth\OtpService;
use Illuminate\Http\Request;
use RuntimeException;

class PasswordResetController extends Controller
{
    public function __construct(
        private OtpService $otp
    ) {
    }

    public function showRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $data = $request->validate([
            'phone' => [
                'required',
                'string',
                'regex:/^09\d{9}$/',
            ],
        ]);

        try {

            $phone = $this->otp->normalizePhone(
                $data['phone']
            );

            $this->otp->sendPasswordResetOtp(
                $phone
            );

            $request->session()->put(
                'password_reset.phone',
                $phone
            );

            return redirect()
                ->route('password.otp')
                ->with(
                    'success',
                    'کد تأیید ارسال شد.'
                );

        } catch (RuntimeException $e) {

            return back()
                ->withErrors([
                    'phone' => $e->getMessage(),
                ])
                ->withInput();
        }
    }
}