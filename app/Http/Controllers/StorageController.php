<?php

namespace App\Http\Controllers;

use App\Services\Storage\StorageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StorageController extends Controller
{
    public function test()
    {
        $storage = app(StorageManager::class);

        $path = 'tests/local-test.txt';

        $content =
            "Storage test successful.\n" .
            "Time: " . now()->toDateTimeString() . "\n";

        $storage->put(
            $content,
            $path,
            'local'
        );

        return response()->json([
            'success' => true,
            'provider' => 'local',
            'path' => $path,
            'exists' => $storage->exists(
                $path,
                'local'
            ),
            'size' => $storage->size(
                $path,
                'local'
            ),
            'url' => $storage->url(
                $path,
                'local'
            ),
            'content' => $storage->get(
                $path,
                'local'
            ),
        ]);
    }

    public function upload(
        Request $request,
        StorageManager $storage
    ) {
        $request->validate([
            'file' => [
                'required',
                'file',
                'max:10240',
            ],
        ]);

        $file = $request->file('file');

        $filename =
            Str::uuid() .
            '.' .
            $file->getClientOriginalExtension();

        $path = 'tests/' . $filename;

        $storedPath = $storage->put(
            $file,
            $path,
            'local'
        );

        return response()->json([
            'success' => true,
            'provider' => 'local',
            'path' => $storedPath,
            'exists' => $storage->exists(
                $storedPath,
                'local'
            ),
            'size' => $storage->size(
                $storedPath,
                'local'
            ),
            'url' => $storage->url(
                $storedPath,
                'local'
            ),
        ]);
    }
}