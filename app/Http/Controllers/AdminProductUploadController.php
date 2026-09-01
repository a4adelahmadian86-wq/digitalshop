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

class AdminProductUploadController extends Controller
{
    private const MAX_BYTES = 209715200;
    private const MIME_TYPES = [
        'pdf' => ['application/pdf'],
        'doc' => ['application/msword', 'application/octet-stream'],
        'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip', 'application/octet-stream'],
    ];

    public function store(Request $request, StorageManager $storageManager): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'file' => ['required', 'file', 'max:204800'],
            'storage_provider_id' => ['nullable', 'integer', 'exists:storage_providers,id'],
        ]);
        $validator->after(function ($validator) use ($request) {
            if (!$request->hasFile('file')) return;
            $file = $request->file('file');
            $extension = strtolower($file->getClientOriginalExtension());
            $mime = strtolower((string) $file->getMimeType());
            if (!array_key_exists($extension, self::MIME_TYPES)) {
                $validator->errors()->add('file', 'فقط PDF، DOC و DOCX مجاز است.');
                return;
            }
            if (!in_array($mime, self::MIME_TYPES[$extension], true)) $validator->errors()->add('file', 'نوع واقعی فایل با پسوند آن سازگار نیست.');
            if ((int) $file->getSize() > self::MAX_BYTES) $validator->errors()->add('file', 'حجم فایل نباید بیشتر از 200MB باشد.');
        });
        $validator->validate();

        $file = $request->file('file');
        $sha256 = hash_file('sha256', $file->getRealPath());
        $duplicate = ProductFile::where('sha256', $sha256)->exists() || ProductUpload::where('sha256', $sha256)->where('status', 'uploaded')->exists();
        if ($duplicate) return response()->json(['ok' => false, 'code' => 'duplicate', 'message' => 'این فایل قبلاً در فروشگاه ثبت یا در حال ثبت است؛ حتی اگر نام فایل متفاوت باشد.'], 422);

        $provider = $request->filled('storage_provider_id')
            ? StorageProvider::findOrFail($request->integer('storage_provider_id'))
            : StorageProvider::where('is_active', true)->where('is_default', true)->first() ?? StorageProvider::where('is_active', true)->orderBy('id')->first();
        abort_unless($provider && $provider->is_active, 422, 'هیچ Storage Provider فعالی برای آپلود وجود ندارد.');

        $extension = strtolower($file->getClientOriginalExtension());
        $storedName = (string) Str::uuid() . '.' . $extension;
        $path = 'products/staging/' . auth()->id() . '/' . date('Y/m') . '/' . $storedName;
        $storedPath = $storageManager->upload($provider, $file, $path);
        $upload = ProductUpload::create([
            'user_id' => auth()->id(), 'storage_provider_id' => $provider->id, 'original_name' => $file->getClientOriginalName(),
            'stored_path' => $storedPath, 'mime_type' => $file->getMimeType(), 'extension' => $extension,
            'size' => (int) $file->getSize(), 'sha256' => $sha256, 'status' => 'uploaded',
        ]);

        return response()->json(['ok' => true, 'file' => ['id' => $upload->id, 'name' => $upload->original_name, 'size' => $upload->size, 'extension' => strtoupper($upload->extension), 'status' => 'uploaded']]);
    }

    public function destroy(ProductUpload $upload, StorageManager $storageManager): JsonResponse
    {
        abort_unless($upload->user_id === auth()->id() && $upload->status === 'uploaded', 403);
        try { $storageManager->provider($upload->storageProvider)->delete($upload->stored_path); } catch (\Throwable $e) { report($e); }
        $upload->update(['status' => 'deleted']);
        return response()->json(['ok' => true]);
    }
}
