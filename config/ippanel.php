<?php

$secretsFile = env(
    'IPPANEL_SECRETS_FILE',
    dirname(base_path())
    . DIRECTORY_SEPARATOR
    . 'secure'
    . DIRECTORY_SEPARATOR
    . 'amir.php'
);

$secrets = require $secretsFile;

return [

    'api_key' => $secrets['ippanel_api_key'] ?? '',

    'base_url' => env(
        'IPPANEL_BASE_URL',
        'https://edge.ippanel.com/v1/api'
    ),

];