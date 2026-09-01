<?php

namespace App\Services\AI;

interface AIProviderInterface
{
    public function available(): bool;
    public function analyze(array $payload): array;
}
