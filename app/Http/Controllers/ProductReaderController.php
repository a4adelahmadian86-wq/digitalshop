<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use App\Services\AI\ProductKnowledgeService;
use Illuminate\Http\Request;

class ProductReaderController extends Controller
{
    public function preview(Product $product, ProductKnowledgeService $knowledge)
    {
        abort_unless($product->is_published, 404);
        $product->load('files');
        $documents = $product->knowledgeDocuments()->with(['chunks','file'])->get();
        if ($documents->isEmpty()) {
            $knowledge->index($product);
            $documents = $product->fresh()->knowledgeDocuments()->with(['chunks','file'])->get();
        }
        $pages = $this->pages($documents, 7);
        return view('products.reader', ['product' => $product, 'pages' => $pages, 'preview' => true, 'pageLimit' => min(7, count($pages))]);
    }

    public function read(Request $request, Product $product)
    {
        abort_unless($request->user(), 403);
        $owned = OrderItem::where('product_id', $product->id)
            ->whereHas('order', fn ($q) => $q->where('user_id', $request->user()->id)->where('status', 'paid'))
            ->exists();
        abort_unless($owned, 403);

        $documents = $product->knowledgeDocuments()->with(['chunks','file'])->get();
        abort_if($documents->isEmpty(), 404);
        $pages = $this->pages($documents, null);
        return view('products.reader', ['product' => $product, 'pages' => $pages, 'preview' => false, 'pageLimit' => count($pages)]);
    }

    private function pages($documents, ?int $limit): array
    {
        $pages = [];
        foreach ($documents as $document) {
            foreach ($document->chunks->sortBy('chunk_no') as $chunk) {
                $pages[] = [
                    'number' => count($pages) + 1,
                    'file' => $document->file?->original_name ?? 'فایل محصول',
                    'extension' => strtoupper($document->file?->extension ?? ''),
                    'content' => $chunk->content,
                    'evidence_id' => $chunk->id,
                ];
                if ($limit !== null && count($pages) >= $limit) return $pages;
            }
        }
        return $pages;
    }
}
