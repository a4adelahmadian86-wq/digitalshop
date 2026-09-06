<?php

namespace App\Services\Wallet;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class WalletService
{
    public function wallet(User $user): Wallet
    {
        return $user->wallet()->firstOrCreate([], [
            'balance' => 0,
            'is_active' => true,
        ]);
    }

    public function credit(Wallet $wallet, int $amount, string $description, ?string $referenceType = null, ?int $referenceId = null, array $metadata = []): WalletTransaction
    {
        if ($amount <= 0) throw new RuntimeException('مبلغ افزایش موجودی باید بیشتر از صفر باشد.');
        return DB::transaction(function () use ($wallet, $amount, $description, $referenceType, $referenceId, $metadata) {
            $wallet = Wallet::query()->whereKey($wallet->id)->lockForUpdate()->firstOrFail();
            if (!$wallet->is_active) throw new RuntimeException('کیف پول فعال نیست.');
            $before = (int) $wallet->balance; $after = $before + $amount;
            $wallet->update(['balance' => $after]);
            return WalletTransaction::create(['wallet_id'=>$wallet->id,'type'=>'credit','amount'=>$amount,'balance_before'=>$before,'balance_after'=>$after,'status'=>'completed','reference_type'=>$referenceType,'reference_id'=>$referenceId,'description'=>$description,'metadata'=>$metadata]);
        });
    }

    public function debit(Wallet $wallet, int $amount, string $description, ?string $referenceType = null, ?int $referenceId = null, array $metadata = []): WalletTransaction
    {
        if ($amount <= 0) throw new RuntimeException('مبلغ برداشت باید بیشتر از صفر باشد.');
        return DB::transaction(function () use ($wallet, $amount, $description, $referenceType, $referenceId, $metadata) {
            $wallet = Wallet::query()->whereKey($wallet->id)->lockForUpdate()->firstOrFail();
            if (!$wallet->is_active) throw new RuntimeException('کیف پول فعال نیست.');
            $before = (int) $wallet->balance;
            if ($before < $amount) throw new RuntimeException('موجودی کیف پول کافی نیست.');
            $after = $before - $amount;
            $wallet->update(['balance' => $after]);
            return WalletTransaction::create(['wallet_id'=>$wallet->id,'type'=>'debit','amount'=>$amount,'balance_before'=>$before,'balance_after'=>$after,'status'=>'completed','reference_type'=>$referenceType,'reference_id'=>$referenceId,'description'=>$description,'metadata'=>$metadata]);
        });
    }
}
