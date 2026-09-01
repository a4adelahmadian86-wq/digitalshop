<?php

namespace App\Http\Controllers;

use App\Models\ProductDraft;
use App\Models\ProductUpload;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminProductDraftController extends Controller
{
    public function show(): JsonResponse
    {
        $draft = ProductDraft::where('user_id', auth()->id())->latest('id')->first();
        if (!$draft) return response()->json(['ok' => true, 'draft' => null]);
        $ids = data_get($draft->payload, 'upload_ids', []);
        $uploads = ProductUpload::where('user_id', auth()->id())->where('status', 'uploaded')->whereIn('id', is_array($ids) ? $ids : [])->get()->map(fn ($file) => [
            'id' => $file->id, 'name' => $file->original_name, 'size' => $file->size, 'extension' => strtoupper($file->extension),
        ])->values();
        return response()->json(['ok' => true, 'draft' => ['id' => $draft->id, 'payload' => $draft->payload ?? [], 'uploads' => $uploads, 'last_saved_at' => optional($draft->last_saved_at)->toIso8601String()]]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate(['draft_id' => ['nullable', 'integer', 'exists:product_drafts,id'], 'payload' => ['required', 'array']]);
        $draft = !empty($data['draft_id']) ? ProductDraft::where('id', $data['draft_id'])->where('user_id', auth()->id())->first() : null;
        if (!$draft) $draft = new ProductDraft(['user_id' => auth()->id()]);
        $allowed = ['title', 'category_id', 'slug', 'price', 'storage_provider_id', 'short_description', 'description', 'upload_ids'];
        $payload = collect($data['payload'])->only($allowed)->all();
        $payload['upload_ids'] = ProductUpload::where('user_id', auth()->id())->where('status', 'uploaded')->whereIn('id', (array) ($payload['upload_ids'] ?? []))->pluck('id')->values()->all();
        $draft->payload = $payload; $draft->last_saved_at = now(); $draft->save();
        return response()->json(['ok' => true, 'draft_id' => $draft->id, 'saved_at' => $draft->last_saved_at->toIso8601String()]);
    }
}
