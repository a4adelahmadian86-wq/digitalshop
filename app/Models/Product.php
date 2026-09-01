<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'storage_provider_id',
        'title',
        'slug',
        'short_description',
        'description',
        'price',
        'file_name',
        'file_path',
        'storage_path',
        'thumbnail',
        'is_published',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function storageProvider(): BelongsTo
    {
        return $this->belongsTo(
            StorageProvider::class
        );
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function downloads()
    {
        return $this->hasMany(Download::class);
    }

    public function orders()
    {
        return $this->belongsToMany(
            Order::class,
            'order_items'
        )->withPivot('price');
    }
}