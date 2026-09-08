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
        if ($this->apiKey !== '') $headers[$headerName] = $prefix . $this->apiKey;
        return Http::withHeaders($headers)->timeout($this->timeout)->retry(2, 300);
    }

    protected function url(string $key): string
    {
        $path = (string) ($this->config[$key] ?? '');
        return $this->endpoint . ($path === '' ? '' : '/' . ltrim($path, '/'));
    }

    public function put(UploadedFile $file, string $path): string
    {
        $request = $this->client()->attach(
            $this->config['file_field'] ?? 'file',
            fopen($file->getRealPath(), 'rb'),
            $file->getClientOriginalName()
        );
        $response = $request->post($this->url('upload_path'), [($this->config['path_field'] ?? 'path') => $path]);
        if ($response->failed()) throw new \RuntimeException('آپلود در Storage API ناموفق بود: HTTP ' . $response->status());
        $json = $response->json();
        $stored = data_get($json, $this->config['path_response'] ?? 'path') ?? data_get($json, 'data.path') ?? data_get($json, 'file.path') ?? data_get($json, 'url');
        if (!$stored) throw new \RuntimeException('Storage API مسیر فایل آپلودشده را برنگرداند.');
        return (string) $stored;
    }

    public function delete(string $path): bool
    {
        $response = $this->client()->delete($this->url('delete_path'), [($this->config['path_field'] ?? 'path') => $path]);
        return $response->successful() || $response->status() === 404;
    }

    public function exists(string $path): bool
    {
        $response = $this->client()->get($this->url('exists_path'), [($this->config['path_field'] ?? 'path') => $path]);
        if ($response->status() === 404 || $response->failed()) return false;
        return (bool) (data_get($response->json(), $this->config['exists_response'] ?? 'exists') ?? true);
    }

    public function download(string $path, ?string $name = null)
    {
        $response = $this->client()->get($this->url('download_path'), [($this->config['path_field'] ?? 'path') => $path]);
        if ($response->failed()) abort($response->status());
        return response($response->body(), Response::HTTP_OK, [
            'Content-Type' => $response->header('Content-Type', 'application/octet-stream'),
            'Content-Disposition' => 'attachment; filename="' . addslashes($name ?: basename($path)) . '"',
        ]);
    }

    public function testConnection(): bool
    {
        $response = $this->client()->get($this->config['test_path'] ? $this->url('test_path') : $this->endpoint);
        return $response->successful();
    }

    public function usage(): array
    {
        $path = trim((string) ($this->config['usage_path'] ?? ''));
        if ($path === '') {
            return [
                'bytes' => (int) ($this->config['used_bytes'] ?? 0),
                'files' => (int) ($this->config['used_files'] ?? 0),
            ];
        }

        try {
            $response = $this->client()->get($this->url('usage_path'));
            if ($response->successful()) {
                $json = $response->json();
                return [
                    'bytes' => (int) (data_get($json, $this->config['usage_bytes_response'] ?? 'used_bytes') ?? data_get($json, 'data.used_bytes') ?? 0),
                    'files' => (int) (data_get($json, $this->config['usage_files_response'] ?? 'used_files') ?? data_get($json, 'data.used_files') ?? 0),
                ];
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return [
            'bytes' => (int) ($this->config['used_bytes'] ?? 0),
            'files' => (int) ($this->config['used_files'] ?? 0),
        ];
    }
}
