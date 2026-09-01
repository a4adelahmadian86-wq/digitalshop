<?php

namespace App\Observers;

use App\Models\AiProductAnalysis;
use App\Models\Product;
use App\Services\AI\ProductContentGuard;
use Illuminate\Support\Str;

class ProductAiObserver
{
    public function saved(Product $product): void
    {
        if (!$product->exists) return;

        $guard = app(ProductContentGuard::class);
        $report = $guard->inspect([
            'title' => $product->title,
            'short_description' => $product->short_description,
            'description' => $product->description,
        ]);

        $product->forceFill([
            'ai_status' => $report['status'],
            'ai_score' => $report['score'],
            'ai_report' => $report,
            'ai_source_hash' => hash('sha256', implode("\n", [
                (string) $product->title,
                (string) $product->short_description,
                (string) $product->description,
                (string) $product->file_name,
                (string) $product->storage_path,
            ])),
        ])->saveQuietly();

        AiProductAnalysis::create([
            'product_id' => $product->id,
            'status' => $report['status'],
            'score' => $report['score'],
            'findings' => $report['findings'],
            'evidence' => $report['evidence'],
            'source_hash' => $product->ai_source_hash,
            'inspected_at' => now(),
        ]);
    }
}
