<?php

namespace App\Services\Finance;

use App\Models\FinancialLedger;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LedgerService
{
    public function postPair(
        string $eventType,
        string $debitCode,
        string $debitName,
        string $creditCode,
        string $creditName,
        int $amount,
        ?User $user = null,
        ?Order $order = null,
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?string $description = null,
        array $metadata = []
    ): array {
        if ($amount <= 0) {
            return [];
        }

        return DB::transaction(function () use (
            $eventType,
            $debitCode,
            $debitName,
            $creditCode,
            $creditName,
            $amount,
            $user,
            $order,
            $referenceType,
            $referenceId,
            $description,
            $metadata
        ) {
            $group = 'FL-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(6));

            $debit = FinancialLedger::create([
                'entry_number' => $group . '-D',
                'event_type' => $eventType,
                'account_code' => $debitCode,
                'account_name' => $debitName,
                'side' => 'debit',
                'amount' => $amount,
                'currency' => 'IRT',
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'user_id' => $user?->id,
                'order_id' => $order?->id,
                'description' => $description,
                'metadata' => array_merge($metadata, ['group' => $group]),
                'posted_at' => now(),
            ]);

            $credit = FinancialLedger::create([
                'entry_number' => $group . '-C',
                'event_type' => $eventType,
                'account_code' => $creditCode,
                'account_name' => $creditName,
                'side' => 'credit',
                'amount' => $amount,
                'currency' => 'IRT',
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'user_id' => $user?->id,
                'order_id' => $order?->id,
                'description' => $description,
                'metadata' => array_merge($metadata, ['group' => $group]),
                'posted_at' => now(),
            ]);

            return [$debit, $credit];
        });
    }

    public function postSale(Order $order): array
    {
        $entries = [];

        if ((int) $order->gateway_amount > 0) {
            $entries = array_merge($entries, $this->postPair(
                'sale',
                'bank.gateway',
                'حساب درگاه / بانک',
                'revenue.sales',
                'درآمد فروش',
                (int) $order->gateway_amount,
                $order->user,
                $order,
                Order::class,
                $order->id,
                'ثبت فروش سفارش ' . $order->order_number
            ));
        }

        if ((int) $order->wallet_amount > 0) {
            $entries = array_merge($entries, $this->postPair(
                'sale_wallet',
                'liability.wallet',
                'بدهی کیف پول کاربران',
                'revenue.sales',
                'درآمد فروش',
                (int) $order->wallet_amount,
                $order->user,
                $order,
                Order::class,
                $order->id,
                'مصرف کیف پول برای سفارش ' . $order->order_number
            ));
        }

        if ((int) $order->tax > 0) {
            $entries = array_merge($entries, $this->postPair(
                'tax',
                'revenue.sales',
                'درآمد فروش',
                'liability.tax',
                'مالیات پرداختنی',
                (int) $order->tax,
                $order->user,
                $order,
                Order::class,
                $order->id,
                'مالیات سفارش ' . $order->order_number
            ));
        }

        return $entries;
    }
}
