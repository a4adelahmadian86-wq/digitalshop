<?php

namespace App\Services\AI;

use App\Models\AiUserEvent;
use App\Models\AiUserProfile;
use Illuminate\Support\Facades\Auth;

class CustomerIntentService
{
    public function record(string $event, ?string $query = null, ?int $productId = null, array $metadata = []): void
    {
        AiUserEvent::create([
            'user_id' => Auth::id(),
            'product_id' => $productId,
            'session_id' => session()->getId(),
            'event' => $event,
            'query' => $query ? mb_substr(trim($query), 0, 500) : null,
            'metadata' => $metadata,
        ]);
    }

    public function profile(): ?AiUserProfile
    {
        $userId = Auth::id();
        if (!$userId) return null;
        return AiUserProfile::firstOrCreate(['user_id' => $userId]);
    }
}
