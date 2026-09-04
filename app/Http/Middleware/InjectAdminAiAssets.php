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

        $headAssets = <<<'HTML'
<link rel="stylesheet" href="{{ADMIN_AI_CSS}}">
HTML;

        $script = <<<'HTML'
<script src="{{ADMIN_AI_JS}}"></script>
HTML;

        $assetCss = asset('css/ai-assistant.css');
        $assetJs = asset('js/ai-assistant.js');

        $headAssets = str_replace('{{ADMIN_AI_CSS}}', e($assetCss), $headAssets);
        $script = str_replace('{{ADMIN_AI_JS}}', e($assetJs), $script);

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
