<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MediaController extends Controller
{
    public function legacy(Request $request, string $path): BinaryFileResponse
    {
        $path = str_replace(['\\', "\0"], ['/', ''], $path);
        $path = ltrim($path, '/');

        if ($path === '' || str_contains($path, '..')) {
            abort(404);
        }

        $candidates = [
            base_path('Images/' . $path),
            public_path('Images/' . $path),
            storage_path('app/public/Images/' . $path),
            base_path($path),
            public_path($path),
        ];

        $file = null;
        foreach ($candidates as $candidate) {
            $real = realpath($candidate);
            if ($real && is_file($real)) {
                $file = $real;
                break;
            }
        }

        if (!$file) {
            abort(404);
        }

        $allowedRoots = array_filter(array_map('realpath', [
            base_path('Images'),
            public_path('Images'),
            storage_path('app/public/Images'),
            base_path(),
            public_path(),
        ]));

        $ok = false;
        foreach ($allowedRoots as $root) {
            if ($root && str_starts_with($file, $root . DIRECTORY_SEPARATOR)) {
                $ok = true;
                break;
            }
        }

        if (!$ok) {
            abort(404);
        }

        $mime = mime_content_type($file) ?: 'application/octet-stream';

        return response()->file($file, [
            'Content-Type' => $mime,
            'Cache-Control' => 'public, max-age=86400',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
