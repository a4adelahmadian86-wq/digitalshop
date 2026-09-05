<?php

namespace App\Services\Storage;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApiStorageProvider implements StorageProviderInterface
{
    public function __construct(
        protected string $endpoint,
        protected string $apiKey,
        protected int $timeout = 120
    ) {
        $this->endpoint = rtrim($this->endpoint, '/');
    }

    protected function client()
    {
        return Http::withToken($this->apiKey)
            ->acceptJson()
            ->timeout($this->timeout);
    }

    public function put(UploadedFile $file, string $path): string
    {
        $response = $this->client()
            ->attach('file', fopen($file->getRealPath(), 'r'), $file->getClientOriginalName())
            ->post($this->endpoint.'/upload', ['path' => $path]);

        if (!$response->successful()) {
            throw new \RuntimeException('API Storage upload failed. HTTP '.$response->status().': '.$response->body());
        }

        $storedPath = $response->json('path');
        if (!is_string($storedPath) || $storedPath === '') {
            throw new \RuntimeException('API Storage did not return a valid storage path.');
        }

        return $storedPath;
    }

    public function delete(string $path): bool
    {
        $response = $this->client()->delete($this->endpoint.'/delete', ['path' => $path]);
        if (!$response->successful()) {
            throw new \RuntimeException('API Storage delete failed. HTTP '.$response->status().': '.$response->body());
        }
        return (bool) $response->json('deleted', false);
    }

    public function exists(string $path): bool
    {
        $response = $this->client()->get($this->endpoint.'/exists', ['path' => $path]);
        if (!$response->successful()) {
            throw new \RuntimeException('API Storage exists check failed. HTTP '.$response->status().': '.$response->body());
        }
        return (bool) $response->json('exists', false);
    }

    public function download(string $path, ?string $name = null): StreamedResponse
    {
        $response = $this->client()->withOptions(['stream' => true])->get($this->endpoint.'/download', ['path' => $path]);
        if (!$response->successful()) {
            throw new \RuntimeException('API Storage download failed. HTTP '.$response->status());
        }

        $body = $response->toPsrResponse()->getBody();
        $downloadName = $name ?: basename($path);
        $headers = ['Content-Type' => $response->header('Content-Type') ?: 'application/octet-stream'];
        if ($length = $response->header('Content-Length')) {
            $headers['Content-Length'] = $length;
        }

        return response()->streamDownload(function () use ($body) {
            while (!$body->eof()) {
                echo $body->read(1024 * 1024);
                if (function_exists('ob_flush')) @ob_flush();
                flush();
            }
            $body->close();
        }, $downloadName, $headers);
    }

    public function testConnection(): bool
    {
        try {
            $response = $this->client()->get($this->endpoint.'/test');
            return $response->successful() && (bool) $response->json('success', false);
        } catch (\Throwable) {
            return false;
        }
    }
}
