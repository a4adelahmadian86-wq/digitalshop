<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StorageProvider extends Model
{
    protected $fillable = [
        'name', 'type', 'scope', 'location', 'priority', 'documentation_url',
        'capabilities', 'config', 'is_active', 'is_default',
    ];

    protected $casts = [
        'config' => 'array',
        'capabilities' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    public const SCOPES = [
        'files' => 'فایل‌ها و دارایی‌ها',
        'backups' => 'بکاپ‌ها',
        'user_data' => 'اطلاعات کاربران',
        'temporary' => 'فایل‌های موقت',
    ];

    public const LOCATIONS = [
        'internal' => 'سرور داخلی',
        'external' => 'سرور خارجی',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeLabel(): string
    {
        return self::SCOPES[$this->scope] ?? (string) $this->scope;
    }

    public function locationLabel(): string
    {
        return self::LOCATIONS[$this->location] ?? (string) $this->location;
    }
}
