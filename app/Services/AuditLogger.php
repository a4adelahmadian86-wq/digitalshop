<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AuditLogger
{
    public function record(Request $request, string $action, ?Model $target = null, array $changes = [], string $outcome = 'success'): void
    {
        AuditLog::create([
            'actor_id' => $request->user()?->id,
            'action' => $action,
            'target_type' => $target ? $target::class : null,
            'target_id' => $target?->getKey(),
            'changes' => $changes ?: null,
            'outcome' => $outcome,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 2000),
        ]);
    }
}