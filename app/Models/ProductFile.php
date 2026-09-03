<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductFile extends Model
{
    protected $fillable = [
        'product_id', 'storage_provider_id', 'original_name', 'stored_name',
        'storage_path', 'mime_type', 'extension', 'size', 'sha256', 'sort_order',
    ];

    protected $casts = ['size' => 'integer', 'sort_order' => 'integer'];

    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function storageProvider(): BelongsTo { return $this->belongsTo(StorageProvider::class); }
}
