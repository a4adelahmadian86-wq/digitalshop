<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use App\Services\AI\ProductKnowledgeService;
use App\Services\Storage\StorageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductReaderController extends Controller
{
    public function preview(Product $product, ProductKnowledgeService $knowledge)
    {
        abort_unless($product->is_published, 404);
        $product->load('files');
        $documents = $product->knowledgeDocuments()->with(['chunks', 'file'])->get();
        if ($documents->isEmpty()) {
            $knowledge->index($product);
            $documents = $product->fresh()->knowledgeDocuments()->with(['chunks', 'file'])->get();
        }

        return view('products.reader', [
            'product' => $product,
            'pages' => $this->pages($documents, 7),
            'preview' => true,
            'pageLimit' => 7,
            'pdfFile' => $product->files->first(fn ($file) => strtolower($file->extension) === 'pdf'),
        ]);
    }

    public function read(Request $request, Product $product)
    {
        abort_unless($request->user(), 403);
        $owned = OrderItem::where('product_id', $product->id)
            ->whereHas('order', fn ($q) => $q->where('user_id', $request->user()->id)->where('status', 'paid'))
            ->exists();
        abort_unless($owned, 403);

        $product->load('files');
        $documents = $product->knowledgeDocuments()->with(['chunks', 'file'])->get();
        abort_if($documents->isEmpty(), 404);
        return view('products.reader', [
            'product' => $product,
            'pages' => $this->pages($documents, null),
            'preview' => false,
            'pageLimit' => null,
            'pdfFile' => $product->files->first(fn ($file) => strtolower($file->extension) === 'pdf'),
        ]);
    }

    public function pdf(Request $request, Product $product, StorageManager $storage)
    {
        abort_unless($product->is_published, 404);
        $file = $product->files()->whereRaw('LOWER(extension) = ?', ['pdf'])->orderBy('sort_order')->first();
        abort_unless($file, 404);

        $owned = false;
        if ($request->user()) {
            $owned = OrderItem::where('product_id', $product->id)
                ->whereHas('order', fn ($q) => $q->where('user_id', $request->user()->id)->where('status', 'paid'))
                ->exists();
        }

        abort_unless($owned || $request->boolean('preview'), 403);
        return $this->streamStorageFile($file, $storage, !$owned);
    }

    private function streamStorageFile($file, StorageManager $storage, bool $preview)
    {
        $provider = $file->storageProvider ?: $storage->defaultProvider();
        abort_unless($provider->is_active, 404);

        if ($provider->type === 'local') {
            $disk = $provider->config['disk'] ?? 'local';
            abort_unless(Storage::disk($disk)->exists($file->storage_path), 404);
            return response()->stream(function () use ($disk, $file) {
                $stream = Storage::disk($disk)->readStream($file->storage_path);
                abort_if($stream === false, 404);
                fpassthru($stream);
                fclose($stream);
            }, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . addslashes($file->original_name) . '"',
                'Cache-Control' => $preview ? 'private, no-store' : 'private, max-age=60',
                'X-Content-Type-Options' => 'nosniff',
                'Accept-Ranges' => 'bytes',
            ]);
        }

        return $storage->provider($provider)->download($file->storage_path, $file->original_name);
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
