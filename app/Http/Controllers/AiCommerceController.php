<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\AI\AIManager;
use App\Services\AI\CustomerIntentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AiCommerceController extends Controller
{
    public function chat(Request $request, CustomerIntentService $intent, AIManager $ai): JsonResponse
    {
        $message = trim((string) $request->input('message'));
        abort_if($message === '', 422, 'پیام خالی است.');

        $intent->record('assistant_message', $message);

        if (!$ai->isAvailable()) {
            return response()->json([
                'ok' => true,
                'available' => false,
                'message' => 'دستیار هوشمند در حال آماده‌سازی است. در حال حاضر نمی‌توانم درباره محصولات ادعای تأییدنشده ارائه کنم.',
                'products' => [],
            ]);
        }

        return response()->json([
            'ok' => true,
            'available' => true,
            'message' => 'پاسخ هوشمند پس از اتصال Provider و جستجوی شواهد محصول فعال می‌شود.',
            'products' => [],
        ]);
    }

    public function product(Product $product, CustomerIntentService $intent): JsonResponse
    {
        abort_unless($product->is_published, 404);
        $intent->record('product_view', null, $product->id);

        return response()->json([
            'ok' => true,
            'product' => [
                'id' => $product->id,
                'title' => $product->title,
                'summary' => $product->ai_summary,
                'ai_status' => $product->ai_status,
                'ai_score' => $product->ai_score,
            ],
        ]);
    }
}
