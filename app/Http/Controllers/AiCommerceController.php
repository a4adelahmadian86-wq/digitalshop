<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\AI\AIManager;
use App\Services\AI\CustomerIntentService;
use App\Services\AI\ProductSearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AiCommerceController extends Controller
{
    public function chat(Request $request, CustomerIntentService $intent, ProductSearchService $search, AIManager $ai): JsonResponse
    {
        $message = trim((string) $request->input('message'));
        abort_if($message === '', 422, 'پیام خالی است.');

        $intent->record('assistant_message', $message);
        $products = $search->search($message, 5);

        $items = $products->map(function (Product $product) {
            return [
                'id' => $product->id,
                'title' => $product->title,
                'slug' => $product->slug,
                'url' => route('product.show', $product),
                'price' => $product->price,
                'score' => $product->smart_score ?? 0,
                'evidence' => array_map(fn ($row) => [
                    'file' => $row['file'],
                    'snippet' => $row['snippet'],
                    'source_hash' => $row['source_hash'],
                ], $product->evidence ?? []),
            ];
        })->values();

        if ($items->isEmpty()) {
            return response()->json([
                'ok' => true,
                'available' => $ai->isAvailable(),
                'message' => 'برای این درخواست، در محتوای واقعی محصولات منتشرشده شواهد کافی پیدا نکردم؛ بنابراین محصولی را به‌عنوان پاسخ قطعی پیشنهاد نمی‌کنم.',
                'products' => [],
                'evidence_based' => true,
            ]);
        }

        $withEvidence = $items->filter(fn ($item) => count($item['evidence']) > 0)->values();
        if ($withEvidence->isEmpty()) {
            return response()->json([
                'ok' => true,
                'available' => $ai->isAvailable(),
                'message' => 'محصولات مشابه پیدا شدند، اما برای این درخواست شواهد کافی از محتوای فایل آن‌ها در پایگاه دانش موجود نیست؛ بنابراین ادعای محتوایی نمی‌کنم.',
                'products' => $items,
                'evidence_based' => true,
            ]);
        }

        $top = $withEvidence->take(3);
        $lines = ['بر اساس محتوای واقعی فایل‌های موجود، این گزینه‌ها بیشترین تطابق را دارند:'];
        foreach ($top as $item) {
            $lines[] = '• ' . $item['title'] . ' — ' . $item['evidence'][0]['snippet'];
        }

        return response()->json([
            'ok' => true,
            'available' => $ai->isAvailable(),
            'message' => implode("\n", $lines),
            'products' => $items,
            'evidence_based' => true,
        ]);
    }

    public function product(Product $product, CustomerIntentService $intent, ProductSearchService $search): JsonResponse
    {
        abort_unless($product->is_published, 404);
        $intent->record('product_view', null, $product->id);
        $recommendations = $search->recommend(auth()->id(), $product->id, 6);

        return response()->json([
            'ok' => true,
            'product' => [
                'id' => $product->id,
                'title' => $product->title,
                'summary' => $product->ai_summary,
                'ai_status' => $product->ai_status,
                'ai_score' => $product->ai_score,
            ],
            'recommendations' => $recommendations->map(fn (Product $item) => [
                'id' => $item->id,
                'title' => $item->title,
                'url' => route('product.show', $item),
                'score' => $item->recommendation_score ?? 0,
            ])->values(),
        ]);
    }
}
