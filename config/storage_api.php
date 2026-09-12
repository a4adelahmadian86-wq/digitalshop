<?php

return [
    'api_key' => env('STORAGE_API_KEY', ''),
    'disk' => env('STORAGE_API_DISK', 'local'),
    'max_upload_kb' => (int) env('STORAGE_API_MAX_UPLOAD_KB', 512000),
];
