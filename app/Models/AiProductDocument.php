<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiProductDocument extends Model
{
    protected $fillable = ['product_id','product_file_id','status','text_length','chunk_count','source_hash','error_message','indexed_at'];
    protected $casts = ['indexed_at' => 'datetime'];
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function file(): BelongsTo { return $this->belongsTo(ProductFile::class, 'product_file_id'); }
    public function chunks(): HasMany { return $this->hasMany(AiProductChunk::class, 'document_id'); }
}
