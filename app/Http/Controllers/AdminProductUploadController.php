<?php

namespace App\Http\Controllers;

use App\Models\ProductFile;
use App\Models\ProductUpload;
use App\Models\StorageProvider;
use App\Services\Storage\StorageManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use ZipArchive;

class AdminProductUploadController extends Controller
{
    private const MAX_BYTES = 209715200;
    private const EXTENSIONS = ['pdf', 'doc', 'docx'];
    private const MIME_TYPES = [
        'pdf' => ['application/pdf'],
        'doc' => ['application/msword', 'application/octet-stream'],
        'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip', 'application/octet-stream'],
    ];

    public function store(Request $request, StorageManager $storageManager): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'file' => ['required', 'file', 'max:209715'],
            'storage_provider_id' => ['nullable', 'integer', 'exists:storage_providers,id'],
        ]);
        $validator->after(function ($validator) use ($request) {
            if (!$request->hasFile('file')) return;
            $file = $request->file('file'); $ext = strtolower($file->getClientOriginalExtension());
            if ($ext === 'zip') return;
            $mime = strtolower((string) $file->getMimeType());
            if (!in_array($ext, self::EXTENSIONS, true)) $validator->errors()->add('file', 'فقط PDF، DOC، DOCX یا ZIP مجاز است.');
            elseif (!in_array($mime, self::MIME_TYPES[$ext], true)) $validator->errors()->add('file', 'نوع واقعی فایل با پسوند آن سازگار نیست.');
            if ((int) $file->getSize() > self::MAX_BYTES) $validator->errors()->add('file', 'حجم فایل نباید بیشتر از 200MB باشد.');
        });
        $validator->validate();

        $file = $request->file('file');
        $provider = $request->filled('storage_provider_id')
            ? StorageProvider::findOrFail($request->integer('storage_provider_id'))
            : StorageProvider::where('is_active', true)->where('is_default', true)->first() ?? StorageProvider::where('is_active', true)->orderBy('id')->first();
        abort_unless($provider && $provider->is_active, 422, 'هیچ Storage Provider فعالی برای آپلود وجود ندارد.');

        if (strtolower($file->getClientOriginalExtension()) === 'zip') return $this->storeZip($file, $provider, $storageManager);
        return response()->json(['ok' => true, 'files' => [$this->storeOne($file, $provider, $storageManager)]]);
    }

    private function storeZip($zipFile, StorageProvider $provider, StorageManager $storageManager): JsonResponse
    {
        $zip = new ZipArchive();
        abort_unless($zip->open($zipFile->getRealPath()) === true, 422, 'فایل ZIP قابل خواندن نیست.');
        $tmp = storage_path('app/upload-extract/' . Str::uuid());
        mkdir($tmp, 0775, true);
        $files = [];
        try {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $entry = $zip->getNameIndex($i);
                if (!$entry || str_ends_with($entry, '/')) continue;
                $safe = str_replace('\\', '/', $entry);
                if (str_contains($safe, '../') || str_starts_with($safe, '/') || preg_match('/^[A-Za-z]:/', $safe)) continue;
                $ext = strtolower(pathinfo($safe, PATHINFO_EXTENSION));
                if (!in_array($ext, self::EXTENSIONS, true)) continue;
                $target = $tmp . DIRECTORY_SEPARATOR . basename($safe);
                copy('zip://' . $zipFile->getRealPath() . '#' . $entry, $target);
                if (is_file($target) && filesize($target) <= self::MAX_BYTES) $files[] = [$target, basename($safe), $ext];
            }
            abort_if(count($files) === 0, 422, 'داخل ZIP هیچ فایل PDF، DOC یا DOCX معتبر پیدا نشد.');
            $result = [];
            foreach ($files as [$path, $name, $ext]) {
                $result[] = $this->storeOne(new \Illuminate\Http\UploadedFile($path, $name, null, null, true), $provider, $storageManager);
            }
            return response()->json(['ok' => true, 'files' => $result, 'archive' => true]);
        } finally {
            $zip->close();
            foreach (glob($tmp . '/*') ?: [] as $p) @unlink($p);
            @rmdir($tmp);
        }
    }

    private function storeOne($file, StorageProvider $provider, StorageManager $storageManager): array
    {
        $sha256 = hash_file('sha256', $file->getRealPath());
        $duplicate = ProductFile::where('sha256', $sha256)->exists() || ProductUpload::where('sha256', $sha256)->where('status', 'uploaded')->exists();
        abort_if($duplicate, 422, 'این فایل قبلاً ثبت شده یا در حال ثبت است.');
        $extension = strtolower($file->getClientOriginalExtension());
        $storedName = Str::uuid() . '.' . $extension;
        $path = 'products/staging/' . auth()->id() . '/' . date('Y/m') . '/' . $storedName;
        $storedPath = $storageManager->upload($provider, $file, $path);
        $upload = ProductUpload::create([
            'user_id' => auth()->id(), 'storage_provider_id' => $provider->id, 'original_name' => $file->getClientOriginalName(),
            'stored_path' => $storedPath, 'mime_type' => $file->getMimeType(), 'extension' => $extension,
            'size' => (int) $file->getSize(), 'sha256' => $sha256, 'status' => 'uploaded',
        ]);
        return ['id' => $upload->id, 'name' => $upload->original_name, 'size' => $upload->size, 'extension' => strtoupper($extension), 'status' => 'uploaded'];
    }

    public function destroy(ProductUpload $upload, StorageManager $storageManager): JsonResponse
    {
        abort_unless($upload->user_id === auth()->id() && $upload->status === 'uploaded', 403);
        try { $storageManager->provider($upload->storageProvider)->delete($upload->stored_path); } catch (\Throwable $e) { report($e); }
        $upload->update(['status' => 'deleted']);
        return response()->json(['ok' => true]);
    }
}
