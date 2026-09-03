<?php

namespace App\Observers;

use App\Models\AiProductAnalysis;
use App\Models\Product;
use App\Services\AI\ProductContentGuard;

class ProductAiObserver
{
    public function saved(Product $product): void
    {
        $report = app(ProductContentGuard::class)->inspect([
            'title' => $product->title,
            'short_description' => $product->short_description,
            'description' => $product->description,
        ]);

        $status = $report['status'];
        $publishAllowed = in_array($status, ['ready_for_review', 'approved'], true);
        $hash = hash('sha256', implode("\n", [
            (string) $product->title,
            (string) $product->short_description,
            (string) $product->description,
            (string) $product->file_name,
            (string) $product->storage_path,
        ]));

        $product->forceFill([
            'ai_status' => $status,
            'ai_score' => $report['score'],
            'ai_report' => $report,
            'ai_source_hash' => $hash,
            'ai_indexed_at' => null,
            'is_published' => $publishAllowed ? $product->is_published : false,
        ])->saveQuietly();

        AiProductAnalysis::create([
            'product_id' => $product->id,
            'status' => $status,
            'score' => $report['score'],
            'findings' => $report['findings'],
            'evidence' => $report['evidence'],
            'source_hash' => $hash,
            'inspected_at' => now(),
        ]);
    }
}
