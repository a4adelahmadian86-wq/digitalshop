<?php

namespace App\Services\AI;

class NullAIProvider implements AIProviderInterface
{
    public function available(): bool { return false; }

    public function analyze(array $payload): array
    {
        return [
            'status' => 'unavailable',
            'score' => null,
            'findings' => [[
                'severity' => 'info',
                'code' => 'ai_not_configured',
                'message' => 'سرویس هوش مصنوعی هنوز پیکربندی نشده است؛ هیچ ادعایی درباره محتوای محصول تولید نشد.',
            ]],
            'evidence' => [],
        ];
    }
}
