<?php

namespace App\Services\AI;

class AIManager
{
    public function __construct(private AIProviderInterface $provider) {}

    public function inspectProduct(array $payload): array
    {
        return $this->provider->analyze($payload);
    }

    public function isAvailable(): bool
    {
        return $this->provider->available();
    }
}
