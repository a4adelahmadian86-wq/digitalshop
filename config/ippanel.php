<?php

$secretsFile = env(
    'IPPANEL_SECRETS_FILE',
    'C:\\xampp\\secure\\amir.php'
);

// API credentials are intentionally kept outside the repository.
// The local/test environment uses the same secure/amir.php file
// used by the main local installation.
if (!is_file($secretsFile)) {
    throw new RuntimeException(
        'IPPANEL secrets file was not found: ' . $secretsFile
        . '. Make sure C:\\xampp\\secure\\amir.php exists.'
    );
}

$secrets = require $secretsFile;

if (!is_array($secrets)) {
    throw new RuntimeException(
        'IPPANEL secrets file must return an array: ' . $secretsFile
    );
}

return [

    'api_key' => $secrets['ippanel_api_key'] ?? '',

    'base_url' => env(
        'IPPANEL_BASE_URL',
        'https://edge.ippanel.com/v1/api'
    ),

];
