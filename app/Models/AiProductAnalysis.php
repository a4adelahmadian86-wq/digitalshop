<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiProductAnalysis extends Model
{
    protected $fillable = [
        'product_id', 'status', 'score', 'findings', 'evidence', 'source_hash', 'inspected_at'
    ];

    protected $casts = [
        'findings' => 'array',
        'evidence' => 'array',
        'inspected_at' => 'datetime',
    ];

    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
}
