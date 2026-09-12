<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class FinancialLedger extends Model
{
    protected $table = 'financial_ledgers';

    protected $fillable = [
        'entry_type', 'account', 'debit', 'credit', 'currency',
        'reference', 'description', 'ledgerable_type', 'ledgerable_id',
        'meta', 'posted_at',
    ];

    protected $casts = [
        'debit' => 'integer',
        'credit' => 'integer',
        'meta' => 'array',
        'posted_at' => 'datetime',
    ];

    public function ledgerable(): MorphTo
    {
        return $this->morphTo();
    }
}
