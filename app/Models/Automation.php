<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Automation extends Model
{
    protected $fillable = [
        'key', 'name', 'description', 'command', 'schedule', 'is_enabled',
        'last_run_at', 'next_run_at', 'last_exit_code', 'last_output',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'last_run_at' => 'datetime',
        'next_run_at' => 'datetime',
    ];

    public function runs(): HasMany
    {
        return $this->hasMany(AutomationRun::class);
    }
}