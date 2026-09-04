<?php

return [
    'endpoint' => env('AI_ENDPOINT'),
    'key' => env('AI_KEY'),
    'model' => env('AI_MODEL', 'gpt-4o-mini'),
    'timeout' => (int) env('AI_TIMEOUT', 45),
    'max_messages' => 12,
];
