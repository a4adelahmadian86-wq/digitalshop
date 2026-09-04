<?php
return [
 'provider'=>env('AI_PROVIDER','null'),'endpoint'=>env('AI_ENDPOINT',''),'key'=>env('AI_API_KEY',''),'model'=>env('AI_MODEL','gemini-2.5-flash'),'timeout'=>(int)env('AI_TIMEOUT',30),
 'fallback_provider'=>env('AI_FALLBACK_PROVIDER','null'),'fallback_endpoint'=>env('AI_FALLBACK_ENDPOINT',''),'fallback_key'=>env('AI_FALLBACK_API_KEY',''),'fallback_model'=>env('AI_FALLBACK_MODEL',''),
 'evaluation_models'=>array_values(array_filter(array_map('trim',explode(',',env('AI_EVALUATION_MODELS','gemini-2.5-flash,gemini-2.5-flash-lite'))))),
 'task_models'=>['shopping_assistant'=>'gemini-2.5-flash','product_recommendation'=>'gemini-2.5-flash-lite','product_review'=>'gemini-2.5-flash','product_description'=>'gemini-2.5-flash','customer_behavior'=>'gemini-2.5-flash-lite','evaluation'=>'gemini-2.5-flash','support_ai'=>'gemini-2.5-flash-lite','structured_json'=>'gemini-2.5-flash','file_analysis'=>'gemini-2.5-flash'],
 'pdf_preview'=>['enabled'=>(bool)env('AI_PDF_PREVIEW_ENABLED',true),'page_limit'=>(int)env('AI_PDF_PREVIEW_PAGES',7),'command'=>env('AI_PDF_PREVIEW_COMMAND','')],
];