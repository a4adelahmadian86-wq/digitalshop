<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StorageApiController extends Controller
{
    /**
     * Test API connection.
     */
    public function test()
    {
        $diskName = config(
            'storage_api.disk',
            'local'
        );

        try {

            $disk = Storage::disk(
                $diskName
            );

            $testPath =
                'storage-api-test/' .
                Str::uuid() .
                '.txt';

            $disk->put(
                $testPath,
                'DigitalShop Storage API Test'
            );

            $exists =
                $disk->exists($testPath);

            $disk->delete($testPath);

            if (!$exists) {
                return response()->json([
                    'success' => false,
                    'message' =>
                        'Storage disk test failed.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' =>
                    'Storage API connection successful.',
                'disk' => $diskName,
            ]);

        } catch (\Throwable $e) {

            report($e);

            return response()->json([
                'success' => false,
                'message' =>
                    'Storage connection failed.',
            ], 500);
        }
    }

    /**
     * Upload a file.
     */
    public function upload(
        Request $request
    ) {
        $request->validate([
            'file' => [
                'required',
                'file',
                'max:' .
                    config(
                        'storage_api.max_upload_kb',
                        512000
                    ),
            ],

            'path' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);

        $path =
            $this->normalizePath(
                $request->input('path')
            );

        if (!$path) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid storage path.',
            ], 422);
        }

        $file =
            $request->file('file');

        $diskName = config(
            'storage_api.disk',
            'local'
        );

        try {

            $storedPath =
                Storage::disk($diskName)
                    ->putFileAs(
                        dirname($path),
                        $file,
                        basename($path)
                    );

            if (!$storedPath) {
                return response()->json([
                    'success' => false,
                    'message' =>
                        'File could not be stored.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'path' => $storedPath,
                'file_name' =>
                    $file->getClientOriginalName(),
                'size' => $file->getSize(),
            ]);

        } catch (\Throwable $e) {

            report($e);

            return response()->json([
                'success' => false,
                'message' =>
                    'Storage upload failed.',
            ], 500);
        }
    }

    /**
     * Check whether a file exists.
     */
    public function exists(
        Request $request
    ) {
        $path =
            $this->normalizePath(
                $request->query('path')
            );

        if (!$path) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid storage path.',
            ], 422);
        }

        $diskName = config(
            'storage_api.disk',
            'local'
        );

        try {

            $exists =
                Storage::disk($diskName)
                    ->exists($path);

            return response()->json([
                'success' => true,
                'exists' => $exists,
                'path' => $path,
            ]);

        } catch (\Throwable $e) {

            report($e);

            return response()->json([
                'success' => false,
                'message' =>
                    'Storage existence check failed.',
            ], 500);
        }
    }

    /**
     * Download a stored file.
     */
    public function download(
        Request $request
    ) {
        $path =
            $this->normalizePath(
                $request->query('path')
            );

        if (!$path) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid storage path.',
            ], 422);
        }

        $diskName = config(
            'storage_api.disk',
            'local'
        );

        try {

            $disk =
                Storage::disk($diskName);

            if (!$disk->exists($path)) {
                return response()->json([
                    'success' => false,
                    'message' =>
                        'File not found.',
                ], 404);
            }

            $name =
                basename($path);

            return $disk->download(
                $path,
                $name
            );

        } catch (\Throwable $e) {

            report($e);

            return response()->json([
                'success' => false,
                'message' =>
                    'Storage download failed.',
            ], 500);
        }
    }

    /**
     * Delete a stored file.
     */
    public function delete(
        Request $request
    ) {
        $path =
            $this->normalizePath(
                $request->input('path')
            );

        if (!$path) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid storage path.',
            ], 422);
        }

        $diskName = config(
            'storage_api.disk',
            'local'
        );

        try {

            $deleted =
                Storage::disk($diskName)
                    ->delete($path);

            return response()->json([
                'success' => true,
                'deleted' => $deleted,
                'path' => $path,
            ]);

        } catch (\Throwable $e) {

            report($e);

            return response()->json([
                'success' => false,
                'message' =>
                    'Storage delete failed.',
            ], 500);
        }
    }

    /**
     * Normalize and validate storage paths.
     */
    private function normalizePath(
        ?string $path
    ): ?string {

        if (!$path) {
            return null;
        }

        $path = trim($path);

        if ($path === '') {
            return null;
        }

        /*
         * جلوگیری از null byte
         */
        if (str_contains($path, "\0")) {
            return null;
        }

        /*
         * فقط slash استاندارد
         */
        if (str_contains($path, '\\')) {
            return null;
        }

        /*
         * مسیر نباید absolute باشد.
         */
        if (Str::startsWith($path, '/')) {
            return null;
        }

        /*
         * جلوگیری از directory traversal
         */
        $segments =
            explode('/', $path);

        foreach ($segments as $segment) {

            if (
                $segment === '' ||
                $segment === '.' ||
                $segment === '..'
            ) {
                return null;
            }
        }

        return implode(
            '/',
            $segments
        );
    }
}