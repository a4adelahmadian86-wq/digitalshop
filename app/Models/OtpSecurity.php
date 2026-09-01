<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpSecurity extends Model
{
    protected $table = 'otp_security';

    protected $fillable = [
        'phone',
        'last_sent_at',
        'daily_sent_count',
        'daily_count_date',
        'login_logout_streak',
        'last_login_at',
        'last_logout_at',
        'sms_blocked_until',
        'restriction_level',
        'last_ip',
    ];

    protected $casts = [
        'last_sent_at' => 'datetime',
        'daily_count_date' => 'date',
        'last_login_at' => 'datetime',
        'last_logout_at' => 'datetime',
        'sms_blocked_until' => 'datetime',
    ];
}