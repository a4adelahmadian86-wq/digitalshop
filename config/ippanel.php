<?php

$secretsFile = env(
    'IPPANEL_SECRETS_FILE',
    dirname(base_path())
    . DIRECTORY_SEPARATOR
    . 'secure'
    . DIRECTORY_SEPARATOR
    . 'amir.php'
);

$secrets = is_file($secretsFile) ? require $secretsFile : [];

return [
    'api_key' => $secrets['ippanel_api_key'] ?? env('IPPANEL_API_KEY', ''),
    'base_url' => env('IPPANEL_BASE_URL', 'https://edge.ippanel.com/v1/api'),
];
