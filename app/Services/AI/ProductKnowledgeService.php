<?php

namespace App\Services\AI;

use App\Models\Product;
use App\Models\ProductFile;
use Illuminate\Support\Facades\DB;

class ProductKnowledgeService
{
    public function __construct(private ProductDocumentExtractor $extractor) {}

    public function index(Product $product): array
    {
        $files = $product->files()->get();
        $documents = [];
        $total = 0;

        foreach ($files as $file) {
            $document = $this->extractor->extract($file);
            $documents[] = $document;
            $total += $document['text_length'];
        }

        $hash = hash('sha256', implode('|', array_map(fn ($d) => ($d['sha256'] ?? '') . ':' . ($d['text_length'] ?? 0), $documents)));
        $successful = count(array_filter($documents, fn ($d) => $d['status'] === 'extracted'));

        $product->forceFill([
            'ai_indexed_at' => $successful > 0 ? now() : null,
            'ai_source_hash' => $hash,
        ])->saveQuietly();

        return [
            'product_id' => $product->id,
            'files' => count($documents),
            'extracted_files' => $successful,
            'total_text_length' => $total,
            'source_hash' => $hash,
            'documents' => $documents,
            'ready' => $successful > 0,
        ];
    }

    public function evidence(Product $product, string $query, int $limit = 5): array
    {
        $query = trim($query);
        if ($query === '') return [];

        $terms = preg_split('/\s+/u', mb_strtolower($query), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $evidence = [];

        foreach ($product->files()->get() as $file) {
            $doc = $this->extractor->extract($file);
            $text = mb_strtolower($doc['text']);
            if ($text === '') continue;

            $position = 0;
            $score = 0;
            foreach ($terms as $term) {
                $count = substr_count($text, $term);
                $score += min($count, 10);
                if ($score && $position === 0) $position = mb_strpos($text, $term) ?: 0;
            }
            if ($score <= 0) continue;

            $original = $doc['text'];
            $start = max(0, $position - 260);
            $snippet = mb_substr($original, $start, 700);
            $evidence[] = [
                'file_id' => $file->id,
                'file' => $file->original_name,
                'score' => $score,
                'snippet' => trim($snippet),
                'source_hash' => $file->sha256,
            ];
        }

        usort($evidence, fn ($a, $b) => $b['score'] <=> $a['score']);
        return array_slice($evidence, 0, max(1, $limit));
    }
}
