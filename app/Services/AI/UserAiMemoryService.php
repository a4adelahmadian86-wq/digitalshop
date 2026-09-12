<?php

namespace App\Services\AI;

use App\Models\User;
use App\Models\UserAiMemory;

class UserAiMemoryService
{
    public function remember(
        User $user,
        string $category,
        string $key,
        string $value,
        int $importance = 50,
        string $source = 'system',
        bool $persistent = true
    ): UserAiMemory {
        return $user->aiMemories()->updateOrCreate(
            ['memory_key' => $key],
            [
                'category' => $category,
                'memory_value' => $value,
                'importance' => max(0, min(100, $importance)),
                'source' => $source,
                'persistent' => $persistent,
                'last_confirmed_at' => now(),
                'expires_at' => null,
            ]
        );
    }

    public function active(User $user)
    {
        return $user->aiMemories()
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->orderByDesc('importance')
            ->orderByDesc('last_confirmed_at')
            ->get();
    }

    public function context(User $user): string
    {
        return $this->active($user)
            ->map(fn ($m) => $m->category.': '.$m->memory_key.'='.$m->memory_value)
            ->implode("\n");
    }

    public function keywords(User $user): array
    {
        $text = $this->active($user)->pluck('memory_value')->implode(' ');
        preg_match_all('/[\p{L}\p{N}]{3,}/u', mb_strtolower($text), $m);

        return array_values(array_unique($m[0] ?? []));
    }
}
