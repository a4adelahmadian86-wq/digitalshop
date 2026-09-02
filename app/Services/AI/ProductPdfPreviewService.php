<?php

namespace App\Services\AI;

use App\Models\ProductFile;
use App\Models\ProductPreview;
use App\Services\Storage\StorageManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class ProductPdfPreviewService
{
    public function make(ProductFile $file, StorageManager $storage): ProductPreview
    {
        abort_unless(strtolower($file->extension) === 'pdf', 422, 'فقط فایل PDF می‌تواند Preview PDF داشته باشد.');
        $command = trim((string) config('ai.pdf_preview.command'));
        abort_if($command === '', 503, 'موتور ساخت PDF Preview روی سرور تنظیم نشده است.');

        $provider = $file->storageProvider ?: $storage->defaultProvider();
        abort_unless($provider->is_active, 404);
        abort_unless($provider->type === 'local', 503, 'ساخت Preview برای Storage Provider فعلی هنوز نیاز به آداپتور stream دارد.');

        $disk = $provider->config['disk'] ?? 'local';
        abort_unless(Storage::disk($disk)->exists($file->storage_path), 404);

        $input = Storage::disk($disk)->path($file->storage_path);
        $dir = storage_path('app/pdf-previews');
        if (!is_dir($dir)) mkdir($dir, 0775, true);
        $name = Str::uuid()->toString() . '.pdf';
        $output = $dir . DIRECTORY_SEPARATOR . $name;
        $limit = max(1, (int) config('ai.pdf_preview.page_limit', 7));

        $command = str_replace(
            ['{input}', '{output}', '{pages}'],
            ['"' . $input . '"', '"' . $output . '"', (string) $limit],
            $command
        );

        $process = Process::fromShellCommandline($command, null, null, null, 180);
        $process->run();
        abort_unless($process->isSuccessful() && is_file($output) && filesize($output) > 0, 500, 'ساخت فایل Preview PDF انجام نشد.');

        $stored = 'products/previews/' . $file->product_id . '/' . $name;
        Storage::disk($disk)->put($stored, fopen($output, 'rb'));
        @unlink($output);

        return ProductPreview::updateOrCreate(
            ['product_file_id' => $file->id, 'source_sha256' => $file->sha256],
            [
                'product_id' => $file->product_id,
                'storage_provider_id' => $provider->id,
                'stored_path' => $stored,
                'page_limit' => $limit,
            ]
        );
    }
}
