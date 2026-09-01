<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductUpload extends Model
{
    protected $fillable = [
        'user_id', 'storage_provider_id', 'original_name', 'stored_path',
        'mime_type', 'extension', 'size', 'sha256', 'status', 'product_id',
    ];

    protected $casts = ['size' => 'integer'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function storageProvider(): BelongsTo { return $this->belongsTo(StorageProvider::class); }
}
