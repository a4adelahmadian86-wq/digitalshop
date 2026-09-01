<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Wallet\WalletService;
use Illuminate\Http\Request;

class AdminWalletController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->where('role', '!=', 'admin')
            ->with('wallet')
            ->orderByDesc('id')
            ->paginate(20);

        return view(
            'admin.wallets.index',
            compact('users')
        );
    }

    public function show(User $user)
    {
        $wallet =
            app(WalletService::class)
                ->wallet($user);

        $transactions =
            $wallet->transactions()
                ->paginate(30);

        return view(
            'admin.wallets.show',
            compact(
                'user',
                'wallet',
                'transactions'
            )
        );
    }

    public function credit(
        Request $request,
        User $user,
        WalletService $walletService
    ) {
        $data = $request->validate([
            'amount' => [
                'required',
                'integer',
                'min:1',
            ],

            'description' => [
                'required',
                'string',
                'min:3',
                'max:500',
            ],
        ], [
            'amount.required' =>
                'مبلغ الزامی است.',

            'description.required' =>
                'توضیح تراکنش الزامی است.',
        ]);

        $walletService->credit(
            $user,
            $data['amount'],
            $data['description'],
            'admin',
            auth()->id(),
            auth()->id()
        );

        return back()->with(
            'success',
            'موجودی کاربر افزایش یافت.'
        );
    }

    public function debit(
        Request $request,
        User $user,
        WalletService $walletService
    ) {
        $data = $request->validate([
            'amount' => [
                'required',
                'integer',
                'min:1',
            ],

            'description' => [
                'required',
                'string',
                'min:3',
                'max:500',
            ],
        ], [
            'amount.required' =>
                'مبلغ الزامی است.',

            'description.required' =>
                'توضیح تراکنش الزامی است.',
        ]);

        $walletService->debit(
            $user,
            $data['amount'],
            $data['description'],
            'admin',
            auth()->id(),
            auth()->id()
        );

        return back()->with(
            'success',
            'موجودی کاربر کاهش یافت.'
        );
    }
}