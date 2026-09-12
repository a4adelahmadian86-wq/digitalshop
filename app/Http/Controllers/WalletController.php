<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WalletTopup;
use App\Models\WalletTransaction;
use App\Services\Wallet\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $wallet = app(WalletService::class)->forUser($user);

        $query = $wallet->transactions()->latest();

        if ($request->filled('type') && in_array($request->type, ['credit', 'debit'], true)) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transactions = $query->paginate(20)->withQueryString();

        $stats = [
            'balance' => (int) $wallet->balance,
            'total_credit' => (int) $wallet->transactions()->where('type', 'credit')->where('status', 'completed')->sum('amount'),
            'total_debit' => (int) $wallet->transactions()->where('type', 'debit')->where('status', 'completed')->sum('amount'),
            'pending_topups' => (int) WalletTopup::where('user_id', $user->id)->where('status', 'pending')->sum('amount'),
            'count' => (int) $wallet->transactions()->count(),
        ];

        $suggested = [50000, 100000, 250000, 500000, 1000000];

        return view('wallet.index', compact('wallet', 'transactions', 'stats', 'suggested'));
    }

    public function topup(Request $request)
    {
        $data = $request->validate([
            'amount' => ['required', 'integer', 'min:10000', 'max:50000000'],
        ], [
            'amount.required' => 'مبلغ شارژ را وارد کنید.',
            'amount.integer' => 'مبلغ نامعتبر است.',
            'amount.min' => 'حداقل مبلغ شارژ ۱۰ هزار تومان است.',
            'amount.max' => 'حداکثر مبلغ شارژ ۵۰ میلیون تومان است.',
        ]);

        $topup = WalletTopup::create([
            'user_id' => auth()->id(),
            'amount' => $data['amount'],
            'status' => 'pending',
            'gateway' => 'zarinpal',
        ]);

        $gateway = app(\App\Services\Payment\ZarinPalGateway::class);
        $url = $gateway->payTopup($topup);

        if (!$url) {
            $topup->update(['status' => 'failed']);

            return back()->with('error', 'اتصال به درگاه پرداخت انجام نشد.');
        }

        return redirect($url);
    }

    public function callback(Request $request, WalletTopup $topup, WalletService $walletService)
    {
        $user = User::findOrFail($topup->user_id);

        if ($topup->status === 'paid') {
            return redirect()
                ->route('wallet.index')
                ->with('success', 'این شارژ قبلاً ثبت شده است.');
        }

        if ($request->query('Status') !== 'OK') {
            $topup->update(['status' => 'failed']);

            return redirect()
                ->route('wallet.index')
                ->with('error', 'پرداخت شارژ لغو شد.');
        }

        $gateway = app(\App\Services\Payment\ZarinPalGateway::class);
        $result = $gateway->verifyTopup($topup, $request->query());

        if (!$result) {
            $topup->update(['status' => 'failed']);

            return redirect()
                ->route('wallet.index')
                ->with('error', 'پرداخت شارژ تأیید نشد.');
        }

        DB::transaction(function () use ($topup, $user, $walletService, $result) {
            $lockedTopup = WalletTopup::where('id', $topup->id)->lockForUpdate()->firstOrFail();

            if ($lockedTopup->status === 'paid') {
                return;
            }

            $lockedTopup->update([
                'status' => 'paid',
                'ref_id' => $result['ref_id'] ?? null,
                'paid_at' => now(),
            ]);

            $walletService->credit(
                $user,
                (int) $lockedTopup->amount,
                'شارژ کیف پول',
                WalletTopup::class,
                $lockedTopup->id,
                ['ref_id' => $result['ref_id'] ?? null]
            );
        });

        return redirect()
            ->route('wallet.index')
            ->with('success', 'کیف پول شما با موفقیت شارژ شد.');
    }

    public function showTransaction(WalletTransaction $transaction)
    {
        abort_unless($transaction->wallet->user_id === auth()->id(), 403);

        return view('wallet.transaction', compact('transaction'));
    }
}
