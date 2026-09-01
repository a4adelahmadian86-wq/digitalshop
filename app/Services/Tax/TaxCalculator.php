<?php

namespace App\Services\Tax;

class TaxCalculator
{
    public function rate(): float
    {
        if (!config('tax.enabled')) {
            return 0;
        }

        return max(
            0,
            (float) config('tax.rate')
        );
    }

    public function calculate(
        float $amount
    ): int {
        $amount = max(
            0,
            $amount
        );

        $rate = $this->rate();

        if ($amount <= 0 || $rate <= 0) {
            return 0;
        }

        $tax = $amount *
            ($rate / 100);

        return config('tax.round')
            ? (int) round($tax)
            : (int) $tax;
    }

    public function total(
        float $amount
    ): int {
        return (int) $amount +
            $this->calculate($amount);
    }
}