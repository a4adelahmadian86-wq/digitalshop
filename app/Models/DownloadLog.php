<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DownloadLog extends Model
{
    protected $fillable = [
        'user_id',
        'order_item_id',
        'ip',
        'user_agent',
        'status',
    ];

    public function item()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }
}