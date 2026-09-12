<?php

namespace App\Services\Wallet;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class WalletService
{
    public function forUser(User $user): Wallet
    {
        return $user->wallet()->firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0, 'currency' => 'IRT', 'is_active' => true]
        );
    }

    public function resolveWallet(User|Wallet $owner): Wallet
    {
        return $owner instanceof Wallet ? $owner : $this->forUser($owner);
    }

    public function credit(
        User|Wallet $owner,
        int $amount,
        string $description,
        ?string $referenceType = null,
        ?int $referenceId = null,
        array $metadata = []
    ): WalletTransaction {
        if ($amount <= 0) {
            throw new RuntimeException('مبلغ افزایش موجودی باید بیشتر از صفر باشد.');
        }

        return DB::transaction(function () use ($owner, $amount, $description, $referenceType, $referenceId, $metadata) {
            $wallet = Wallet::query()
                ->whereKey($this->resolveWallet($owner)->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (!$wallet->is_active) {
                throw new RuntimeException('کیف پول فعال نیست.');
            }

            if ($referenceType && $referenceId) {
                $existing = WalletTransaction::query()
                    ->where('wallet_id', $wallet->id)
                    ->where('type', 'credit')
                    ->where('reference_type', $referenceType)
                    ->where('reference_id', $referenceId)
                    ->where('status', 'completed')
                    ->first();

                if ($existing) {
                    return $existing;
                }
            }

            $before = (int) $wallet->balance;
            $after = $before + $amount;

            $wallet->update(['balance' => $after]);

            return WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'type' => 'credit',
                'amount' => $amount,
                'balance_before' => $before,
                'balance_after' => $after,
                'status' => 'completed',
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'description' => $description,
                'metadata' => $metadata,
            ]);
        });
    }

    public function debit(
        User|Wallet $owner,
        int $amount,
        string $description,
        ?string $referenceType = null,
        ?int $referenceId = null,
        array $metadata = []
    ): WalletTransaction {
        if ($amount <= 0) {
            throw new RuntimeException('مبلغ برداشت باید بیشتر از صفر باشد.');
        }

        return DB::transaction(function () use ($owner, $amount, $description, $referenceType, $referenceId, $metadata) {
            $wallet = Wallet::query()
                ->whereKey($this->resolveWallet($owner)->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (!$wallet->is_active) {
                throw new RuntimeException('کیف پول فعال نیست.');
            }

            if ($referenceType && $referenceId) {
                $existing = WalletTransaction::query()
                    ->where('wallet_id', $wallet->id)
                    ->where('type', 'debit')
                    ->where('reference_type', $referenceType)
                    ->where('reference_id', $referenceId)
                    ->where('status', 'completed')
                    ->first();

                if ($existing) {
                    return $existing;
                }
            }

            $before = (int) $wallet->balance;

            if ($before < $amount) {
                throw new RuntimeException('موجودی کیف پول کافی نیست.');
            }

            $after = $before - $amount;

            $wallet->update(['balance' => $after]);

            return WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'type' => 'debit',
                'amount' => $amount,
                'balance_before' => $before,
                'balance_after' => $after,
                'status' => 'completed',
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'description' => $description,
                'metadata' => $metadata,
            ]);
        });
    }

    public function refund(
        User|Wallet $owner,
        int $amount,
        string $description,
        ?string $referenceType = null,
        ?int $referenceId = null,
        array $metadata = []
    ): WalletTransaction {
        $metadata = array_merge(['kind' => 'refund'], $metadata);

        return $this->credit($owner, $amount, $description, $referenceType, $referenceId, $metadata);
    }
}
