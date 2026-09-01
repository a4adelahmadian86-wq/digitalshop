<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StorageApiKey
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        $configuredKey = config(
            'storage_api.api_key'
        );

        if (
            !$configuredKey ||
            !is_string($configuredKey)
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Storage API is not configured.',
            ], 500);
        }

        /*
         * استاندارد:
         *
         * Authorization: Bearer YOUR_API_KEY
         */

        $authorization =
            $request->header('Authorization');

        $providedKey = null;

        if (
            $authorization &&
            preg_match(
                '/^Bearer\s+(.+)$/i',
                $authorization,
                $matches
            )
        ) {
            $providedKey = trim(
                $matches[1]
            );
        }

        /*
         * برای تست و سازگاری، X-Storage-Key
         * را هم قبول می‌کنیم.
         */

        if (!$providedKey) {
            $providedKey =
                $request->header(
                    'X-Storage-Key'
                );
        }

        if (
            !$providedKey ||
            !hash_equals(
                $configuredKey,
                $providedKey
            )
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Storage API key.',
            ], 401);
        }

        return $next($request);
    }
}