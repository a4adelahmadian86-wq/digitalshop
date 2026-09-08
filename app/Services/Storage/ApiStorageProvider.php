<?php

namespace App\Services\Storage;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class ApiStorageProvider implements StorageProviderInterface
{
    public function __construct(
        protected string $endpoint,
        protected string $apiKey,
        protected int $timeout = 120,
        protected array $config = []
    ) {
        $this->endpoint = rtrim($endpoint, '/');
    }

    protected function client()
    {
        $headers = ['Accept' => 'application/json'];
        $headerName = $this->config['header_name'] ?? 'Authorization';
        $prefix = $this->config['header_prefix'] ?? 'Bearer ';
        if ($this->apiKey !== '') {
            $headers[$headerName] = $prefix . $this->apiKey;
        }
        return Http::withHeaders($headers)->timeout($this->timeout)->retry(2, 300);
    }

    protected function url(string $key): string
    {
        return $this->endpoint . '/' . ltrim((string) ($this->config[$key] ?? ''), '/');
    }

    public function put(UploadedFile $file, string $path): string
    {
        $url = $this->url('upload_path');
        $field = $this->config['file_field'] ?? 'file';
        $pathField = $this->config['path_field'] ?? 'path';

        $request = $this->client()->attach($field, fopen($file->getRealPath(), 'rb'), $file->getClientOriginalName());
        $response = $request->post($url, [$pathField => $path]);

        if ($response->failed()) {
            throw new \RuntimeException('آپلود در Storage API ناموفق بود: HTTP ' . $response->status());
        }

        $json = $response->json();
        $stored = data_get($json, $this->config['path_response'] ?? 'path')
            ?? data_get($json, 'data.path')
            ?? data_get($json, 'file.path')
            ?? data_get($json, 'url');

        if (!$stored) {
            throw new \RuntimeException('Storage API مسیر فایل آپلودشده را برنگرداند.');
        }

        return (string) $stored;
    }

    public function delete(string $path): bool
    {
        $deleteUrl = $this->url('delete_path');
        $pathField = $this->config['path_field'] ?? 'path';
        $response = $this->client()->delete($deleteUrl, [$pathField => $path]);
        return $response->successful() || $response->status() === 404;
    }

    public function exists(string $path): bool
    {
        $existsUrl = $this->url('exists_path');
        $pathField = $this->config['path_field'] ?? 'path';
        $response = $this->client()->get($existsUrl, [$pathField => $path]);
        if ($response->status() === 404) return false;
        if ($response->failed()) return false;
        return (bool) (data_get($response->json(), $this->config['exists_response'] ?? 'exists') ?? true);
    }

    public function download(string $path, ?string $name = null)
    {
        $downloadUrl = $this->url('download_path');
        $pathField = $this->config['path_field'] ?? 'path';
        $response = $this->client()->get($downloadUrl, [$pathField => $path]);
        if ($response->failed()) abort($response->status());
        return response($response->body(), Response::HTTP_OK, [
            'Content-Type' => $response->header('Content-Type', 'application/octet-stream'),
            'Content-Disposition' => 'attachment; filename="' . addslashes($name ?: basename($path)) . '"',
        ]);
    }

    public function testConnection(): bool
    {
        $testPath = $this->config['test_path'] ?? '';
        if ($testPath !== '') {
            $response = $this->client()->get($this->endpoint . '/' . ltrim($testPath, '/'));
            return $response->successful();
        }
        $response = $this->client()->get($this->endpoint);
        return $response->successful();
    }
}
