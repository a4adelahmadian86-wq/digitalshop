<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WalletTopup;
use App\Services\Wallet\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $wallet = $user->wallet()
            ->firstOrCreate(
                [
                    'user_id' => $user->id,
                ],
                [
                    'balance' => 0,
                    'currency' => 'IRT',
                ]
            );

        $transactions = $wallet
            ->transactions()
            ->paginate(20);

        return view(
            'wallet.index',
            compact(
                'wallet',
                'transactions'
            )
        );
    }

    public function topup(
        Request $request
    ) {
        $data = $request->validate([
            'amount' => [
                'required',
                'integer',
                'min:10000',
                'max:50000000',
            ],
        ], [
            'amount.required' =>
                'مبلغ شارژ را وارد کنید.',

            'amount.integer' =>
                'مبلغ نامعتبر است.',

            'amount.min' =>
                'حداقل مبلغ شارژ ۱۰ هزار تومان است.',

            'amount.max' =>
                'حداکثر مبلغ شارژ ۵۰ میلیون تومان است.',
        ]);

        $topup = WalletTopup::create([
            'user_id' => auth()->id(),
            'amount' => $data['amount'],
            'status' => 'pending',
            'gateway' => 'zarinpal',
        ]);

        /*
         * این قسمت باید به Gateway abstraction موجود پروژه وصل شود.
         *
         * فعلاً همان ZarinPalGateway موجود پروژه را استفاده می‌کنیم.
         */

        $gateway =
            app(\App\Services\Payment\ZarinPalGateway::class);

        $url = $gateway->payTopup($topup);

        if (!$url) {
            $topup->update([
                'status' => 'failed',
            ]);

            return back()->with(
                'error',
                'اتصال به درگاه پرداخت انجام نشد.'
            );
        }

        return redirect($url);
    }

    public function callback(
        Request $request,
        WalletTopup $topup,
        WalletService $walletService
    ) {
        /*
         * بدون Session
         *
         * مالکیت از خود Topup مشخص می‌شود.
         */

        $user = User::findOrFail(
            $topup->user_id
        );

        /*
         * Idempotency:
         *
         * اگر قبلاً موفق شده، دوباره موجودی اضافه نمی‌کنیم.
         */

        if ($topup->status === 'paid') {
            return redirect()
                ->route('wallet.index')
                ->with(
                    'success',
                    'این شارژ قبلاً ثبت شده است.'
                );
        }

        /*
         * پرداخت لغوشده
         */

        if (
            $request->query('Status') !== 'OK'
        ) {
            $topup->update([
                'status' => 'failed',
            ]);

            return redirect()
                ->route('wallet.index')
                ->with(
                    'error',
                    'پرداخت شارژ لغو شد.'
                );
        }

        /*
         * Verify
         */

        $gateway =
            app(\App\Services\Payment\ZarinPalGateway::class);

        $result = $gateway->verifyTopup(
            $topup,
            $request->query()
        );

        if (!$result) {
            $topup->update([
                'status' => 'failed',
            ]);

            return redirect()
                ->route('wallet.index')
                ->with(
                    'error',
                    'پرداخت شارژ تأیید نشد.'
                );
        }

        /*
         * Critical section
         *
         * دوباره رکورد را lock می‌کنیم.
         */

        DB::transaction(function () use (
            $topup,
            $user,
            $walletService,
            $result
        ) {

            $lockedTopup =
                WalletTopup::where(
                    'id',
                    $topup->id
                )
                ->lockForUpdate()
                ->firstOrFail();

            if (
                $lockedTopup->status === 'paid'
            ) {
                return;
            }

            $lockedTopup->update([
                'status' => 'paid',
                'ref_id' =>
                    $result['ref_id'] ?? null,
                'paid_at' => now(),
            ]);

            $walletService->credit(
                $user,
                (int) $lockedTopup->amount,
                'شارژ کیف پول',
                WalletTopup::class,
                $lockedTopup->id
            );
        });

        return redirect()
            ->route('wallet.index')
            ->with(
                'success',
                'کیف پول شما با موفقیت شارژ شد.'
            );
    }
}