<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InjectAdminAiAssets
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (!$response->isSuccessful()) {
            return $response;
        }

        $contentType = (string) $response->headers->get('Content-Type');
        $content = $response->getContent();

        if ($content === false || !str_contains(strtolower($contentType), 'text/html')) {
            return $response;
        }

        if (str_contains($content, 'ai-assistant.js')) {
            return $response;
        }

        $headAssets = '<meta name="csrf-token" content="' . e(csrf_token()) . '">' . "\n";
        $headAssets .= '<link rel="stylesheet" href="' . e(asset('css/ai-assistant.css')) . '">';
        $script = '<script src="' . e(asset('js/ai-assistant.js')) . '"></script>';

        if (stripos($content, '</head>') !== false) {
            $content = preg_replace('/<\/head>/i', $headAssets . "\n</head>", $content, 1);
        }

        if (stripos($content, '</body>') !== false) {
            $content = preg_replace('/<\/body>/i', $script . "\n</body>", $content, 1);
        } else {
            $content .= "\n" . $script;
        }

        $response->setContent($content);

        return $response;
    }
}