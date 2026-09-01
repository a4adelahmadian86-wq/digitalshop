<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StorageAccount extends Model
{
    protected $fillable = [
        'provider',
        'name',
        'email',
        'bucket',
        'endpoint',
        'capacity_bytes',
        'used_bytes',
        'credentials',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getFreeBytesAttribute()
    {
        return max(
            0,
            $this->capacity_bytes - $this->used_bytes
        );
    }

    public function getUsedPercentAttribute()
    {
        if (!$this->capacity_bytes) {
            return 0;
        }

        return round(
            ($this->used_bytes / $this->capacity_bytes) * 100,
            1
        );
    }
}