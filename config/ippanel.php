<?php

$secretsFile = env(
    'IPPANEL_SECRETS_FILE',
    dirname(base_path())
    . DIRECTORY_SEPARATOR
    . 'secure'
    . DIRECTORY_SEPARATOR
    . 'amir.php'
);

$secrets = [];

// The secrets file is optional for local development.
// Never stop Laravel from booting just because the SMS provider
// credentials are not available on this machine.
if (is_file($secretsFile)) {
    $loadedSecrets = require $secretsFile;

    if (is_array($loadedSecrets)) {
        $secrets = $loadedSecrets;
    }
}

return [

    'api_key' => $secrets['ippanel_api_key']
        ?? env('IPPANEL_API_KEY', ''),

    'base_url' => env(
        'IPPANEL_BASE_URL',
        'https://edge.ippanel.com/v1/api'
    ),

];
