<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPreview extends Model
{
    protected $fillable = ['product_id','product_file_id','storage_provider_id','stored_path','page_limit','source_sha256','excerpt'];
    protected $casts = ['page_limit'=>'integer'];
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function productFile(): BelongsTo { return $this->belongsTo(ProductFile::class); }
    public function storageProvider(): BelongsTo { return $this->belongsTo(StorageProvider::class); }
}
