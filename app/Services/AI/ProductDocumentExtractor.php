<?php

namespace App\Services\AI;

use App\Models\ProductFile;
use Illuminate\Support\Str;

class ProductDocumentExtractor
{
    private const ALLOWED = ['pdf', 'doc', 'docx'];

    public function extract(ProductFile $file): array
    {
        $extension = strtolower((string) $file->extension);
        if (!in_array($extension, self::ALLOWED, true)) {
            return $this->result($file, '', 'unsupported', 'فرمت فایل برای استخراج دانش پشتیبانی نمی‌شود.');
        }

        $path = $this->resolvePath($file);
        if (!$path || !is_file($path) || !is_readable($path)) {
            return $this->result($file, '', 'unavailable', 'فایل برای استخراج در دسترس نیست.');
        }

        if ($extension === 'pdf') {
            return $this->extractPdf($file, $path);
        }

        return $this->extractOffice($file, $path, $extension);
    }

    private function extractPdf(ProductFile $file, string $path): array
    {
        $text = '';
        $command = trim((string) config('ai.pdf_to_text_command', ''));
        if ($command !== '') {
            $safe = escapeshellarg($path);
            $text = (string) shell_exec(str_replace('{file}', $safe, $command));
        }

        if ($text === '') {
            $text = $this->readEmbeddedText($path);
        }

        $status = trim($text) !== '' ? 'extracted' : 'needs_ocr';
        return $this->result($file, $this->clean($text), $status, $status === 'extracted' ? null : 'PDF متن قابل استخراج مستقیم نداشت؛ OCR باید در مرحله پردازش فعال شود.');
    }

    private function extractOffice(ProductFile $file, string $path, string $extension): array
    {
        if ($extension === 'docx') {
            $zip = new \ZipArchive();
            if ($zip->open($path) === true) {
                $xml = $zip->getFromName('word/document.xml') ?: '';
                $zip->close();
                $text = strip_tags(str_replace(['</w:p>', '</w:tab>'], ["\n", "\t"], $xml));
                return $this->result($file, $this->clean(html_entity_decode($text)), trim($text) !== '' ? 'extracted' : 'empty', trim($text) !== '' ? null : 'محتوای متنی DOCX پیدا نشد.');
            }
        }

        $command = trim((string) config('ai.office_to_text_command', ''));
        $text = $command !== '' ? (string) shell_exec(str_replace('{file}', escapeshellarg($path), $command)) : '';
        return $this->result($file, $this->clean($text), trim($text) !== '' ? 'extracted' : 'needs_converter', trim($text) !== '' ? null : 'برای DOC به مبدل متنی امن نیاز است.');
    }

    private function resolvePath(ProductFile $file): ?string
    {
        try {
            $provider = $file->storageProvider;
            if (!$provider) return null;
            $root = method_exists($provider, 'getLocalPath') ? $provider->getLocalPath($file->storage_path) : null;
            if ($root && is_file($root)) return $root;
        } catch (\Throwable) {}

        $candidate = storage_path('app/' . ltrim((string) $file->storage_path, '/\\'));
        return is_file($candidate) ? $candidate : null;
    }

    private function readEmbeddedText(string $path): string
    {
        $raw = @file_get_contents($path, false, null, 0, 8 * 1024 * 1024);
        if (!$raw) return '';
        $raw = preg_replace('/[^\x09\x0A\x0D\x20-\x7E\x{0600}-\x{06FF}]+/u', ' ', $raw) ?? '';
        return preg_replace('/\s{3,}/u', "\n", $raw) ?? $raw;
    }

    private function clean(string $text): string
    {
        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', ' ', $text) ?? $text;
        $text = preg_replace('/\n{3,}/u', "\n\n", $text) ?? $text;
        return trim(Str::limit($text, 2_000_000, ''));
    }

    private function result(ProductFile $file, string $text, string $status, ?string $message): array
    {
        return [
            'file_id' => $file->id,
            'name' => $file->original_name,
            'extension' => strtolower((string) $file->extension),
            'status' => $status,
            'text' => $text,
            'text_length' => mb_strlen($text),
            'sha256' => $file->sha256,
            'message' => $message,
        ];
    }
}
