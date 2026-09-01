<?php

namespace App\Http\Controllers;

use App\Models\ProductDraft;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminProductDraftController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $draft = ProductDraft::where('user_id', auth()->id())->latest('id')->first();

        return response()->json([
            'ok' => true,
            'draft' => $draft ? [
                'id' => $draft->id,
                'payload' => $draft->payload ?? [],
                'last_saved_at' => optional($draft->last_saved_at)->toIso8601String(),
            ] : null,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'draft_id' => ['nullable', 'integer', 'exists:product_drafts,id'],
            'payload' => ['required', 'array'],
        ]);

        $draft = !empty($data['draft_id'])
            ? ProductDraft::where('id', $data['draft_id'])->where('user_id', auth()->id())->first()
            : null;

        if (!$draft) {
            $draft = new ProductDraft(['user_id' => auth()->id()]);
        }

        $allowed = ['title', 'category_id', 'slug', 'price', 'storage_provider_id', 'short_description', 'description', 'upload_ids', 'is_published'];
        $payload = collect($data['payload'])->only($allowed)->all();

        $draft->payload = $payload;
        $draft->last_saved_at = now();
        $draft->save();

        return response()->json([
            'ok' => true,
            'draft_id' => $draft->id,
            'saved_at' => $draft->last_saved_at->toIso8601String(),
        ]);
    }
}
