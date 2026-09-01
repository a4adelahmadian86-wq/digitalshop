<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductQuestion extends Model
{
    protected $fillable = ['product_id','user_id','parent_id','body','is_published'];
    protected $casts = ['is_published' => 'boolean'];
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function parent(): BelongsTo { return $this->belongsTo(self::class,'parent_id'); }
    public function answers(): HasMany { return $this->hasMany(self::class,'parent_id'); }
}
