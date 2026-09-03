<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiProductChunk extends Model
{
    protected $fillable = ['product_id','document_id','chunk_no','content','content_hash','metadata'];
    protected $casts = ['metadata' => 'array'];
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function document(): BelongsTo { return $this->belongsTo(AiProductDocument::class, 'document_id'); }
}
