<?php
return [
 'provider'=>env('AI_PROVIDER','null'),
 'endpoint'=>env('AI_ENDPOINT',''),
 'key'=>env('AI_API_KEY',''),
 'model'=>env('AI_MODEL',''),
 'timeout'=>(int)env('AI_TIMEOUT',60),
 'evaluation_models'=>array_values(array_filter(array_map('trim',explode(',',env('AI_EVALUATION_MODELS',''))))),
 'pdf_preview'=>['enabled'=>(bool)env('AI_PDF_PREVIEW_ENABLED',true),'page_limit'=>(int)env('AI_PDF_PREVIEW_PAGES',7),'command'=>env('AI_PDF_PREVIEW_COMMAND','')],
];