<?php

return [
    'pdf_preview' => [
        'enabled' => (bool) env('AI_PDF_PREVIEW_ENABLED', true),
        'page_limit' => (int) env('AI_PDF_PREVIEW_PAGES', 7),
        'command' => env('AI_PDF_PREVIEW_COMMAND', ''),
    ],
];
