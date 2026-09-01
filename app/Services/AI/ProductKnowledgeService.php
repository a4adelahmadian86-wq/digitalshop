<?php

namespace App\Services\AI;

use App\Models\AiProductChunk;
use App\Models\AiProductDocument;
use App\Models\Product;

class ProductKnowledgeService
{
    public function __construct(private ProductDocumentExtractor $extractor) {}

    public function index(Product $product): array
    {
        $files = $product->files()->get();
        $documents = [];
        $total = 0;

        foreach ($files as $file) {
            $extracted = $this->extractor->extract($file);
            $total += (int) ($extracted['text_length'] ?? 0);
            $sourceHash = (string) ($extracted['sha256'] ?? $file->sha256 ?? '');

            $document = AiProductDocument::firstOrNew([
                'product_id' => $product->id,
                'product_file_id' => $file->id,
            ]);

            $document->fill([
                'status' => $extracted['status'],
                'text_length' => $extracted['text_length'],
                'source_hash' => $sourceHash,
                'error_message' => $extracted['error'] ?? null,
                'indexed_at' => $extracted['status'] === 'extracted' ? now() : null,
            ]);
            $document->save();

            if ($extracted['status'] === 'extracted' && trim($extracted['text']) !== '') {
                $document->chunks()->delete();
                $chunks = $this->chunkText($extracted['text']);
                foreach ($chunks as $number => $content) {
                    AiProductChunk::create([
                        'product_id' => $product->id,
                        'document_id' => $document->id,
                        'chunk_no' => $number + 1,
                        'content' => $content,
                        'content_hash' => hash('sha256', $content),
                        'metadata' => [
                            'source_file_id' => $file->id,
                            'source_file' => $file->original_name,
                        ],
                    ]);
                }
                $document->update(['chunk_count' => count($chunks)]);
            } else {
                $document->chunks()->delete();
                $document->update(['chunk_count' => 0]);
            }

            $documents[] = [
                'id' => $document->id,
                'file_id' => $file->id,
                'file' => $file->original_name,
                'status' => $extracted['status'],
                'text_length' => $extracted['text_length'],
                'chunk_count' => $document->chunk_count,
                'source_hash' => $sourceHash,
                'error' => $extracted['error'] ?? null,
            ];
        }

        $hash = hash('sha256', implode('|', array_map(
            fn ($d) => ($d['source_hash'] ?? '') . ':' . ($d['text_length'] ?? 0),
            $documents
        )));
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
        $terms = $this->terms($query);
        if (!$terms) return [];

        $documents = $product->knowledgeDocuments()->with(['chunks', 'file'])->get();
        if ($documents->isEmpty()) {
            $this->index($product);
            $documents = $product->fresh()->knowledgeDocuments()->with(['chunks', 'file'])->get();
        }

        $evidence = [];
        foreach ($documents as $document) {
            foreach ($document->chunks as $chunk) {
                $text = mb_strtolower($chunk->content);
                $score = 0;
                $position = null;
                foreach ($terms as $term) {
                    $count = substr_count($text, $term);
                    if ($count > 0 && $position === null) $position = mb_strpos($text, $term);
                    $score += min($count, 8);
                }
                if ($score <= 0) continue;

                $original = $chunk->content;
                $start = max(0, (int) ($position ?? 0) - 220);
                $evidence[] = [
                    'document_id' => $document->id,
                    'chunk_id' => $chunk->id,
                    'file' => $document->file?->original_name ?? 'فایل محصول',
                    'score' => $score,
                    'snippet' => trim(mb_substr($original, $start, 650)),
                    'source_hash' => $document->source_hash,
                ];
            }
        }

        usort($evidence, fn ($a, $b) => $b['score'] <=> $a['score']);
        return array_slice($evidence, 0, max(1, $limit));
    }

    private function chunkText(string $text): array
    {
        $text = trim(preg_replace('/\s+/u', ' ', $text) ?? '');
        if ($text === '') return [];

        $size = 1200;
        $overlap = 180;
        $chunks = [];
        $length = mb_strlen($text);
        $start = 0;

        while ($start < $length) {
            $chunk = mb_substr($text, $start, $size);
            if (mb_strlen($chunk) < 40) break;
            $chunks[] = trim($chunk);
            if ($start + $size >= $length) break;
            $start += $size - $overlap;
        }
        return $chunks;
    }

    private function terms(string $text): array
    {
        $text = mb_strtolower(trim($text));
        $text = preg_replace('/[^\p{L}\p{N}\s-]+/u', ' ', $text) ?? '';
        $stop = ['و','در','از','به','برای','با','را','که','این','آن','یک','من','می','میشه','میخواهم','می‌خواهم','دنبال','نیاز','است','هست','the','and','for','with'];
        $terms = preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        return array_values(array_unique(array_filter($terms, fn ($term) => mb_strlen($term) >= 2 && !in_array($term, $stop, true))));
    }
}
