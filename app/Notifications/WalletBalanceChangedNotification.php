<?php

namespace App\Notifications;

use App\Models\WalletTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WalletBalanceChangedNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected WalletTransaction $transaction
    ) {
    }

    public function via($notifiable): array
    {
        return [
            'database',
        ];
    }

    public function toArray($notifiable): array
    {
        $credit =
            $this->transaction->type === 'credit';

        return [
            'type' => $credit
                ? 'wallet_credit'
                : 'wallet_debit',

            'icon' => $credit
                ? 'wallet-plus'
                : 'wallet-minus',

            'color' => $credit
                ? 'success'
                : 'danger',

            'title' => $credit
                ? 'افزایش موجودی کیف پول'
                : 'کاهش موجودی کیف پول',

            'message' => $credit
                ? 'مبلغ ' .
                    number_format($this->transaction->amount) .
                    ' تومان به کیف پول شما اضافه شد.'
                : 'مبلغ ' .
                    number_format($this->transaction->amount) .
                    ' تومان از کیف پول شما کسر شد.',

            'amount' =>
                (int) $this->transaction->amount,

            'balance_after' =>
                (int) $this->transaction->balance_after,

            'transaction_id' =>
                $this->transaction->id,

            'url' =>
                route('wallet.index'),
        ];
    }
}